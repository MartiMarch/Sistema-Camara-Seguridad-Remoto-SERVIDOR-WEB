<?php  
    class MySQL{
        private $conexion;
        private $db;
        
        public function __construct()
        {
        }
        
        public function conexion()
        {
            $this->conexion = mysqli_connect('localhost', 'root', '3+UNO=cuatro') or die("No se pudo conectar a la base de datos.");
            $this->db = mysqli_select_db($this->conexion, "sgcsr") or die ("No se pudo conectar a la base de datos.");
            return $this->conexion;
        }
        
        public function close()
        {
            $this->conexion = null;
        }

        public function validarUsuario($nombre, $contraseña)
        {
            require_once("Modelo/Encriptador.php");
            $validacion = false;  
            $con = $this->conexion();
            if(!is_null($con))
            {
                $encriptador = new Encriptador();
                $consulta =  mysqli_query($con, "SELECT * FROM clientes WHERE nombre='$nombre'");
                while($fila = mysqli_fetch_array($consulta))
                {
                    $contraseñaYsalt = $contraseña . $fila['salt'];
                    $contraseñaDesencriptada = $encriptador->desencriptar($fila['password'], $contraseñaYsalt);
                    if($fila['nombre'] == $nombre and $contraseñaDesencriptada == $contraseñaYsalt)
                    {
                        $validacion = true;
                    }
                }
            }
            
            return $validacion;
        }
        
        public function obtenerVideos()
        {
            require_once ("../Modelo/Video.php");
            $con = $this->conexion();
            $videos = array();
            if(!is_null($con))
            {
                $consulta =  mysqli_query($con, "SELECT * FROM videos");
                while($fila = mysqli_fetch_array($consulta))
                {
                    $video = new Video($fila['id'], $fila['year'], $fila['day'], $fila['month'], $fila['seconds'], $fila['hour'], $fila['minutes']);
                    array_push($videos, $video);
                }
            }
            return $videos;
        }
        
        public function obtenerCamars($nombreCliente, $contraseñaAdmin)
        {
            require_once ("../Modelo/Camara.php");
            require_once ("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $camaras = array();
            $encriptador = new Encriptador();
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "SELECT * FROM camarasclientes WHERE nombreCliente='" . $nombreCliente . "'");
                while($fila = mysqli_fetch_array($consulta))
                {
                    $camara = new Camara($encriptador->desencriptar($fila['urlCamara'], $contraseñaAdmin), $fila['nombreCamara'], $fila['estado']);
                    array_push($camaras, $camara);
                }
            }
            mysqli_close($con);
            unset($con);
            unset($encriptador);
            unset($consulta);
            unset($fila);
            
            return $camaras;
        }
        
        public function renombrarCamara($nombre, $nombreCliente, $url, $contraseñaAdmin)
        {
            require_once ("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $encriptador = new Encriptador();
            $url = $encriptador->encriptar($url, $contraseñaAdmin);
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "UPDATE camarasclientes SET nombreCamara='" . $nombre . "' WHERE nombreCliente='" . $nombreCliente . "' AND urlCamara='" . $url . "'");
            }
            mysqli_close($con);
            unset($con);
            unset($encriptador);
            unset($consulta);
            unset($fila);
        }
        
        public function cambiarEstadoCamara($nombreCliente, $url, $contraseñaAdmin)
        {
            require_once ("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $encriptador = new Encriptador();
            $url = $encriptador->encriptar($url, $contraseñaAdmin);
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "SELECT estado FROM camarasclientes WHERE nombreCliente='" . $nombreCliente . "' AND urlCamara='" . $url . "'");
                while($fila = mysqli_fetch_array($consulta))
                {
                    $estado = $fila['estado'];
                }
                if($estado == "ACTIVADA")
                {
                    $estado = "DESACTIVADA";
                    $consulta = mysqli_query($con, "UPDATE camarasclientes SET estado='" . $estado . "' WHERE nombreCliente='" . $nombreCliente . "' AND urlCamara='" . $url . "'");
                }
                else
                {
                    $estado = "ACTIVADA";
                    $consulta = mysqli_query($con, "UPDATE camarasclientes SET estado='" . $estado . "' WHERE nombreCliente='" . $nombreCliente . "' AND urlCamara='" . $url . "'");
                }
            }
            mysqli_close($con);
            unset($con);
            unset($encriptador);
            unset($consulta);
            unset($fila);
        }
        
        public function getCorreo($nombre, $contraseñaAdmin)
        {
            require_once ("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $encriptador = new Encriptador();
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "SELECT email FROM clientes WHERE nombre='" . $nombre . "'");
                while($fila = mysqli_fetch_array($consulta))
                {
                    $correo = $fila['email'];
                    $correo = $encriptador->desencriptar($correo, $contraseñaAdmin);
                }
            }
            unset($con);
            unset($encriptador);
            unset($consulta);
            unset($fila);
            return $correo;
        }
        
        public function cambiarCorreo($nombre, $nuevoCorreo, $contraseñaAdministrador)
        {
            require_once("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $encriptador = new Encriptador();
            $nuevoCorreo = $encriptador->encriptar($nuevoCorreo, $contraseñaAdministrador);
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "UPDATE clientes SET email='" . $nuevoCorreo . "' WHERE nombre='" . $nombre . "'");
            }
            mysqli_close($con);
            unset($con);
            unset($encriptador);
            unset($contraseñaAdministrador);
            unset($consulta);
            unset($nuevoCorreo);
        }
        
        public function cambiarContraseña($nombre, $clave)
        {
            require_once("../Modelo/Encriptador.php");
            $con = $this->conexion();
            $encriptador = new Encriptador();
            $datos = $encriptador->generarHash($clave);
            if(!is_null($con))
            {
                $consulta = mysqli_query($con, "UPDATE clientes SET password='" . $datos[0] . "' WHERE nombre='" . $nombre . "'");
                $consulta = mysqli_query($con, "UPDATE clientes SET salt='" . $datos[1] . "' WHERE nombre='" . $nombre . "'");
            }
            mysqli_close($con);
            unset($con);
            unset($encriptador);
            unset($contraseñaAdministrador);
            unset($consulta);
            unset($nuevoCorreo);
        }
    }
?>

