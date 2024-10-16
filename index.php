<?php
require_once 'config.php';
require_once 'classes/TicketProviderInterface.php';
require_once 'classes/VividSeatsProvider.php';
require_once 'classes/SeatGeekProvider.php';


$url = $_GET['url'] ?? '';

// PARA QUE LA APP FUNCIONE ES NECESARIO ACCEDER AL ENLACE Y PASAR EL CAPTCHA

if ($url) {
    $provider = new VividSeatsProvider();

    if ($provider) {
        try {
            $tickets = $provider->getTickets($url);
            echo "<h2>Entradas disponibles:</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Sector</th><th>Fila</th><th>Precio</th></tr>";
            foreach ($tickets as $ticket) {
                echo "<tr>
                    <td>{$ticket['sector']}</td>
                    <td>{$ticket['row']}</td>
                    <td>{$ticket['price']}</td>
                </tr>";
            }
            echo "</table>";
        } catch (Exception $e) {
            echo "Error al obtener las entradas: " . $e->getMessage();
        }
    } else {
        echo "URL no soportada.";
    }
} else {
    echo "Por favor, proporcione una URL de evento.";
}
?>