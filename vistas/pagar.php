<?php
session_start();

// Permitir acceso si el usuario está autenticado o pendiente de pago
if (
    !isset($_SESSION['verificado']) &&
    !isset($_SESSION['usuario_pendiente'])
) {
    header("Location: login.php"); 
    exit;
}

// Si el usuario está pendiente, usar ese ID
if (isset($_SESSION['usuario_pendiente'])) {
    $userId = $_SESSION['usuario_pendiente'];
} elseif (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    die("Error: No hay sesión de usuario activa");
}

// Validar que userId es un número válido
if (!$userId || !is_numeric($userId) || $userId <= 0) {
    die("Error: ID de usuario inválido: " . htmlspecialchars($userId));
}

$precio = 10.00;
?>
<!doctype html>
<html>
<head>
  <!-- bootstrap / estilos -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <title>Suscripción PayPal</title>
    <link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
</head>
<body>

<h2>Plan Mensual — $<?= number_format($precio,2) ?> USD</h2>

<!-- Contenedor botón -->
<div id="paypal-button-container"></div>

<!-- SDK: reemplaza TU_CLIENT_ID_SANDBOX por tu Client ID -->
<script src="https://www.paypal.com/sdk/js?client-id=AdEwrrrKqCwI0E_Pb9mKbwJkH5VELvUatThFVmkfyKx1loqvEwSLYOMEcaQixS7gXpH09uUT7I600G6X&currency=USD"></script>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{ amount: { value: "<?= $precio ?>" } }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Datos a enviar
            const payload = {
                orderID: data.orderID,
                transactionID: details.id,
                userID: <?= intval($userId) ?>,
                monto: <?= floatval($precio) ?>
            };
            
            console.log("Enviando pago:", payload);
            
            // Enviar al backend para registrar pago y activar suscripción
            return fetch("../controladores/paypal_controlador.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(resp => {
                console.log("Respuesta del servidor:", resp);
                if (resp.status === "ok") {
                    alert("✅ Pago completado");
                    window.location = "gracias.php";
                } else {
                    alert("❌ Error backend:\n" + (resp.message || "Error desconocido") + 
                          (resp.debug ? "\n\nDebug: " + JSON.stringify(resp.debug) : ""));
                }
            })
            .catch(err => {
                console.error("Error fetch:", err);
                alert("❌ Error al conectar con el servidor: " + err.message);
            });
        });
    },
    onError: function(err) { 
        console.error("Error PayPal:", err); 
        alert("❌ Error con PayPal: " + err.message);
    }
}).render('#paypal-button-container');
</script>

</body>
</html>
