<?php
    class Encriptador
    {
        private $digitos = array('A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'B', 'b', 'C', 'c', 'D', 'd', 'F', 'f', 'G', 'g', 'H', 'h', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'Ð', 'n', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '?', '┐', '<', '>', '*', '-', '+', '=', '[', ']', '{', '}', ':', ',', ';', '_');
        
        public function __construct(){}
        
        public function encriptar($texto, $clave)
        {
            $clave = $this->getSecreto($clave);
            $textoEncriptado = base64_encode(openssl_encrypt($texto, "AES-128-ECB", $clave, OPENSSL_RAW_DATA));
            return $textoEncriptado;
        }
        
        public function desencriptar($texto, $clave)
        {
            $clave = $this->getSecreto($clave);
            $resultado = openssl_decrypt(base64_decode($texto), "AES-128-ECB", $clave, OPENSSL_RAW_DATA);
            return $resultado;
        }
        
        public function getSecreto($clave)
        {
            if (strlen($clave) < 16) 
            {
                $clave = str_pad("$clave", 16, "0"); 
            }
            else if (strlen($clave) > 16)
            {
                $clave = substr($clave, 0, 16); 
            }
            
            return $clave;
        }
        
        public function generarHash($clave)
        {
            $datos = array();
            $salt = "";
            for($i = 0; $i < 16; ++$i)
            {
                $posicion = rand(0, count($this->digitos));
                $salt = $salt . $this->digitos[$posicion];
            }
            $clave = $clave . $salt;
            $hash = $this->encriptar($clave, $clave);
            array_push($datos, $hash);
            array_push($datos, $salt);
            
            return $datos;
        }
    }
?>