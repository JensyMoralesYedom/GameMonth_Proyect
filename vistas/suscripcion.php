<?php
session_start();

// Protegemos la vista
if (!isset($_SESSION["verificado"])) {
    header("Location: login.php");
    exit;
}

// Definir el precio de la suscripción (puedes cambiarlo)
$precio = 10.00; // 10 USD por ejemplo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Suscripción – Pago con PayPal</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .card {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 25px;
            margin-top: 40px;
        }
        .price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #00d4ff;
        }
    </style>
</head>
<body>

<!-- HEADER que me pasaste -->
<div>
    <header class="sticky-top bg-primary">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Mi Web</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menu">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="biblioteca.php">Biblioteca</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pag</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pag</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</div>

<!-- CONTENIDO -->
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card text-center">

                <h2 class="mb-3">Plan Mensual</h2>
                <p>Acceso ilimitado a todos los videojuegos de la plataforma.</p>

                <div class="price">$<?= number_format($precio, 2) ?> USD</div>

                <hr>

                <h4>Pagar con PayPal</h4>

                <!-- Contenedor del botón -->
                <div id="paypal-button-container" class="mt-3"></div>

            </div>
        </div>
    </div>

</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID_SANDBOX&currency=USD"></script>

<script>
// Crear botón PayPal
paypal.Buttons({

    // Crear orden de pago
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: "<?= $precio ?>", // precio dinámico desde PHP
                }
            }]
        });
    },

    // Si el pago fue aprobado
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {

            // Enviar pago al backend
            fetch("../controladores/paypal_controlador.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    orderID: data.orderID,
                    transactionID: details.id,
                    userID: "<?= $_SESSION['user_id'] ?>",
                    monto: "<?= $precio ?>"
                })
            })
            .then(res => res.json())
            .then(resp => {
                if (resp.status === "ok") {
                    alert("Pago completado correctamente");
                    window.location = "gracias.php";
                }
            });

        });
    },

    // Si hay un error
    onError: function(err) {
        console.error(err);
        alert("Ocurrió un error al procesar el pago.");
    }

}).render('#paypal-button-container');
</script>


</body>
</html>
