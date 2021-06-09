<?php
class Camara {
    private $url;
    private $nombre;
    private $estado;
    
    public function __construct($url, $nombre, $estado) {
        $this->url = $url;
        $this->nombre = $nombre;
        $this->estado = $estado;
    }
    
    function getUrl() {
        return $this->url;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEstado() {
        return $this->estado;
    }
}
