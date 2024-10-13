<?php
require_once 'TicketProviderInterface.php';

class VividSeatsProvider implements TicketProviderInterface {
    public function getTickets($url) {
        $apiUrl = $this->getApiUrlFromEventUrl($url);

        $data = $this->fetchApiData($apiUrl);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }

        $tickets = [];
        if (isset($data['tickets']) && is_array($data['tickets'])) {
            foreach ($data['tickets'] as $ticket) {
                $tickets[] = [
                    'sector' => $ticket['s'] ?? 'N/A',
                    'row' => $ticket['r'] ?? 'N/A',
                    'price' => $ticket['p'] ?? 'N/A',
                    'id' => $ticket['i'] ?? 'N/A',
                    'note' => $ticket['n'] ?? '',
                    'badges' => $ticket['badges'] ?? []
                ];
            }
        }

        return $tickets;
    }

    /**
     * Obtiene la URL de la API a partir de la URL del evento.
     *
     * @param string $eventUrl
     * @return string
     */
    private function getApiUrlFromEventUrl($eventUrl) {

        preg_match('/production\/(\d+)/', $eventUrl, $matches);
        $eventId = $matches[1] ?? null;

        if (!$eventId) {
            throw new Exception("No se pudo extraer el ID del evento de la URL.");
        }

        return "https://www.vividseats.com/hermes/api/v1/listings?productionId=" . $eventId;
    }

    /**
     * Realiza una solicitud GET a la API y devuelve la respuesta.
     *
     * @param string $apiUrl
     * @return string
     */
    private function fetchApiData($apiUrl) {
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36'
        ]);
    
        $response = curl_exec($ch);
    
        if ($response === false) {
            throw new Exception('Error en la solicitud cURL: ' . curl_error($ch));
        }
    
        curl_close($ch);

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }
    
        return $data;
    }
}
?>
