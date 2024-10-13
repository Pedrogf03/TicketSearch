<?php
interface TicketProviderInterface {
    /**
     * Obtener entradas de un evento a partir de una URL.
     *
     * @param string $url
     * @return array
     */
    public function getTickets($url);
}
?>