<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../Controlador/PHPMailer-master/src/Exception.php");
require_once("../Controlador/PHPMailer-master/src/PHPMailer.php");
require_once("../Controlador/PHPMailer-master/src/SMTP.php");

class Movimiento extends PHPMailer {
    protected $exceptions;
    private $HOST = 'smtp.gmail.com';
    private $PORT = 587;
    private $correo;
    private $contraseña;
    
    public function __construct($exceptions = null) {
        parent::__construct($exceptions);
    }
    
    public function recibirParametros($direccion, $puerto)
    {
        if(!$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)){
                echo("Error cereando socket, " . socket_strerror(socket_last_error()) . "\n");
        }
        else
        {
            socket_connect($socket, $direccion, $puerto);
            $contador = 0;
            while(true)
            {
                if(!$entrada = @socket_read($socket, 2048, PHP_NORMAL_READ))
                {
                    break;
                }
                else
                {
                    if($contador == 0)
                    {
                        $this->correo = $entrada;
                    }
                    if($contador == 2)
                    {
                        $this->contraseña = $entrada;
                    }
                    ++$contador;
                }
            }
        }
        socket_close($socket);
    }
    
    public function enviarCorreo($correoReceptor, $titulo, $mensaje, $numeroSecreto)
    {
        $correo = new PHPMailer(true);
        $correo->SMTPDebug = 0;
        $correo->isSMTP();
        $correo->Host = $this->HOST;
        $correo->Port = $this->PORT;
        $correo->SMTPAuth = true;
        $correo->SMTPSecure = 'tls';
        
        $correo->Username = $this->correo;
        $correo->Password = $this->contraseña;
        
        $correo->setFrom($this->correo, "SGCSR");
        $correo->addAddress($correoReceptor);
        
        $correo->isHTML(true);
        $correo->Subject = $titulo;
        $correo->Body = $mensaje . " " . $numeroSecreto;
        
        try
        {
            $correo->send();
        }
        catch(Exception $e)
        {
            echo "No se pudo enviar el número secreto al correo especificado.";
        }
    }
}
?>
