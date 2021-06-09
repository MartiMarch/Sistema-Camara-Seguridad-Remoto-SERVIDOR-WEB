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
        
        public function videosValidos($fechaInicial, $fechaFinal)
        {
            $videosAceptados = array();
            if($this->vlidarFecha($fechaFinal) and $this->vlidarFecha($fechaInicial))
            {
                require_once("Video.php");
                require_once("MySQL.php");
                $sql = new MySQL();
                $videos = array();
                $videos = $sql->obtenerVideos();
                $fechaInicial = $this->añadirCeros($fechaInicial);
                $fechaFinal = $this->añadirCeros($fechaFinal);
                for($i = 0; $i < count($videos); ++$i)
                {
                    $año = strval($videos[$i]->getYear());
                    $mes = strval($videos[$i]->getMonth());
                    $dia = strval($videos[$i]->getDay());
                    $fecha = $dia . "-" . $mes . "-" . $año;
                    $fecha = $this->añadirCeros($fecha);
                    if($fechaInicial < $fecha and $fechaFinal > $fecha)
                    {
                        array_push($videosAceptados, $videos[$i]);
                    }
                }
            }
            else
            {
                echo("<script type='text/javascript'>alert(Alguna de las fechas es incorrecta.);</script>");
            }
            return $videosAceptados;
        }
        
        public function vlidarFecha($fecha)
        {
            $fecha = str_replace("/", "-", $fecha);
            $formato = DateTime::createFromFormat("d-m-Y", $fecha);
            return $formato;
        }
        
        public function añadirCeros($fecha)
        {
            $resultado = date("d.m.Y", strtotime($fecha));
            $resultado = str_replace(".", "-", $resultado);
            return strtotime($resultado);
        }
    }
?>