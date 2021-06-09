<?php
    class Cliente
    {
        private $nombre;
        private $contraseña;
        private $contraseñaAdministrador;
        
        public function __construct() {}
        
        public function setNombre($nombre)
        {
            $this->nombre = $nombre;
        }
        
        public function setContraseña($contraseña)
        {
            $this->contraseña = $contraseña;
        }
                
        public function getNombre()
        {
            return $this->nombre;
        }
        
        public function getContrseña()
        {
            return $this->contraseña;
        }
        
        public function getContraseñaAdministrador()
        {
            return $this->contraseñaAdministrador;
        }
        
        public function recibirContraseña($direccion, $puerto)
        {
            if(!$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)){
                echo("Error cereando socket, " . socket_strerror(socket_last_error()) . "\n");
            }
            else
            {
                socket_connect($socket, $direccion, $puerto);
                if(!$mensaje = socket_read($socket, 2048, PHP_NORMAL_READ))
                {
                    echo("Error al recibir mensaje. " . socket_strerror(socket_last_error()) . "\n");
                }
                else
                {
                    $this->contraseñaAdministrador = $mensaje;
                }
            }
            socket_close($socket);
        }
    }
?>