<?php
// controladores/paypal_controlador.php
header('Content-Type: application/json; charset=utf-8');

session_start();

require_once __DIR__ . '/../modelos/conexion.php';
require_once __DIR__ . '/../modelos/pagos_modelo.php';
require_once __DIR__ . '/../modelos/suscripciones_modelo.php';

try {
    // Leer JSON crudo enviado desde el frontend
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'JSON inválido o cuerpo vacío.']);
        exit;
    }

    // Si el frontend no envía userID, intentar usar usuario_pendiente en sesión
    $userID_from_json = isset($data['userID']) ? (int)$data['userID'] : null;
    $userID_session = isset($_SESSION['usuario_pendiente']) ? (int)$_SESSION['usuario_pendiente'] : null;

    // Preferir userID de la sesión pendiente si existe (registro reciente)
    $userID = $userID_session ?: $userID_from_json;

    if (!$userID) {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'No se recibió userID ni existe usuario_pendiente en sesión.']);
        exit;
    }

    // Campos requeridos (orderID y transactionID y monto)
    $required = ['orderID', 'transactionID', 'monto'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => "Falta campo requerido: $field"]);
            exit;
        }
    }

    $orderID = $data['orderID'];
    $transactionID = $data['transactionID'];
    $monto = (float)$data['monto'];

    // --- OPCIONAL: Verificar la orden en PayPal (activa solo si config está presente) ---
    // Lee las credenciales desde variables de entorno o config. Si no están, saltamos la verificación.
    $paypalClient = getenv('PAYPAL_CLIENT') ?: null;
    $paypalSecret = getenv('PAYPAL_SECRET') ?: null;

    if ($paypalClient && $paypalSecret) {
        $auth = base64_encode("$paypalClient:$paypalSecret");
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic $auth",
                "Content-Type: application/x-www-form-urlencoded"
            ],
        ]);
        $resp = curl_exec($ch);

        // Guardar errores cURL
        if ($resp === false) {
            $err = curl_error($ch);
            curl_reset($ch);
            unset($ch);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error cURL al obtener token PayPal: ' . $err]);
            exit;
        }

        $respObj = json_decode($resp, true);
        curl_reset($ch);
        unset($ch);

        if (!isset($respObj['access_token'])) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'No se obtuvo access_token de PayPal.']);
            exit;
        }

        $accessToken = $respObj['access_token'];

        // Verificar la orden
        $ch2 = curl_init();
        curl_setopt_array($ch2, [
            CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders/" . urlencode($orderID),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json"
            ],
        ]);
        $orderResp = curl_exec($ch2);
        if ($orderResp === false) {
            $err = curl_error($ch2);
            curl_reset($ch2);
            unset($ch2);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error cURL al verificar orden: ' . $err]);
            exit;
        }
        $orderObj = json_decode($orderResp, true);
        curl_reset($ch2);
        unset($ch2);

        $orderStatus = $orderObj['status'] ?? null;
        if ($orderStatus !== 'COMPLETED' && $orderStatus !== 'APPROVED') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Orden PayPal no completada. Estado: ' . ($orderStatus ?? 'desconocido'), 'order' => $orderObj]);
            exit;
        }
    }
    // --- FIN VERIFICACIÓN OPCIONAL ---

    // 1) Crear suscripción y obtener id
    $id_suscripcion = SuscripcionesModelo::crearSuscripcion($userID, $monto);
    if (!$id_suscripcion) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la suscripción.']);
        exit;
    }

    // 2) Registrar pago
    $paymentSaved = PagosModelo::registrarPago($userID, $id_suscripcion, $monto, $transactionID);
    if (!$paymentSaved) {
        // opcional: eliminar suscripción si tu modelo lo permite
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el pago.']);
        exit;
    }

    // 3) Si el usuario vino desde registro reciente (usuario_pendiente), activamos sesión
    if (isset($_SESSION['usuario_pendiente']) && $_SESSION['usuario_pendiente'] == $userID) {
        $_SESSION['verificado'] = "si";
        $_SESSION['user_id'] = $userID;
        // Opcional: username si lo tienes en sesión o BD
        if (!isset($_SESSION['username'])) {
            // intentar cargar username desde DB (opcional)
            // $stmt = Conexion::conectar()->prepare("SELECT usuario FROM usuarios WHERE id = ?");
            // $stmt->execute([$userID]);
            // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // if($row) $_SESSION['username'] = $row['usuario'];
        }
        unset($_SESSION['usuario_pendiente']);
    }

    // Responder éxito
    echo json_encode(['status' => 'ok', 'message' => 'Pago registrado y suscripción activada.']);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    // Log en error log en lugar de imprimir (mejor para producción)
    error_log("PDO error en paypal_controlador: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error de base de datos.']);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en paypal_controlador: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error interno.']);
    exit;
}
