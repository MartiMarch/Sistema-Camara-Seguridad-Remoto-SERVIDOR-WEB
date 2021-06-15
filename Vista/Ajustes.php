<?php
    $rutaProyecto = $_SERVER['DOCUMENT_ROOT'];
    $rutaProyecto = str_replace("/", "\\", $rutaProyecto);
    exec("taskkill /IM ffmpeg.exe /F");
    exec("del /S /Q " .  $rutaProyecto . "\PhpProject1\Streaming\*");
    session_start();
    if(!isset($_SESSION['cliente']))
    {
        header('Location: ../index.php');
    }
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
    {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        exit;
    }
    require_once("../Modelo/MySQL.php");
    require_once("../Modelo/Cliente.php");
    require_once("../Modelo/Movimiento.php");
?>
<!DOCTYPE>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Videos</title>
        <style>
            table, th, td
            {
                border: 1px solid black;
                padding: 5px;
            }
            table
            {
                border-spacing: 15px;
            }
        </style>
    </head>
    <body>
        <center>
        <br>
            <table>
                <tr>
                    <td>
                        <form method='post' method='post' target='_blank'>
                            <button type='submit' name='cambiarCorreo' value='$i'>Cambiar correo</button>
                        </form>
                    </td>
                    <td>
                        <form method='post' method='post'>
                            <button type='submit' name='cambiarContraseña' value='$i'>Cambiar contraseña</button>
                        </form>
                    </td>
                </tr>
            </table>
        <br>
        </center>
    </body>
</html>
<?php
    if(isset($_POST['cambiarCorreo']))
    {
        ob_end_clean();
        $numeroAleatorio = 0;
        for($i = 0; $i < 5; ++$i)
        {
            $numeroAleatorio = ($numeroAleatorio * 10) + rand(0, 9);
        }
        $sql = new MySQL();
        $_SESSION['numeroAleatorio'] = serialize($numeroAleatorio);
        $cliente = unserialize($_SESSION['cliente']);
        $correo = $sql->getCorreo($cliente->getNombre(), $cliente->getContraseñaAdministrador());
        $titulo = "Cambio de correo.";
        $mensaje = "El número secreto es ";
        $movimiento = new Movimiento(null);
        $movimiento->recibirParametros("localhost", "6060");
        $movimiento->enviarCorreo($correo, $titulo, $mensaje, $numeroAleatorio);        
        echo("
                <!DOCTYPE>
                <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <title>Modificar Correo</title>
                    </head>
                    <body>
                        <center>
                            <br>
                            <p>Correo actual: '$correo'</p>
                            <form method='post'>
                                <p>Introduce el número secreto enviado a tu correo: </p>
                                <input type='text' name='numeroSecreto'>
                                <p>Introduce el nuevo correo: </p>
                                <input type='text' name='nuevoCorreo'>
                                <br>
                                <br>
                                <br>
                                <button typr='submit' name='confirmarCorreo'>Confirmar cambio</button>
                            </form>
                        </center>
                    </body>
                </html>
            ");   
        unset($sql);
        unset($cliente);
        unset($movimiento);
        unset($titulo);
        unset($mensaje);
    }
    else if(isset($_POST['confirmarCorreo']))
    {
        ob_end_clean();
        $sql = new MySQL();
        $cliente = unserialize($_SESSION['cliente']);
        $nuevoCorreo = $_REQUEST['nuevoCorreo'];
        $numeroSecreto = $_REQUEST['numeroSecreto'];
        $numeroAleatorio = unserialize($_SESSION['numeroAleatorio']);
        if($numeroAleatorio == $numeroSecreto)
        {
            $sql->cambiarCorreo($cliente->getNombre(), $nuevoCorreo, $cliente->getContraseñaAdministrador());
            echo("<br><center>Correo modificado correctamente.</center>");
        }
        else
        {
            echo("<br><center>El número secreto es incorrecto.</center>");
        }
        unset($sql);
    }
    else if(isset($_POST['cambiarContraseña']))
    {
        ob_end_clean();
        $sql = new MySQL();
        $numeroAleatorio = 0;
        for($i = 0; $i < 5; ++$i)
        {
            $numeroAleatorio = ($numeroAleatorio * 10) + rand(0, 9);
        }
        $sql = new MySQL();
        $_SESSION['numeroAleatorio'] = serialize($numeroAleatorio);
        $cliente = unserialize($_SESSION['cliente']);
        $titulo = "Cambio de la clave secreta.";
        $mensaje = "El número secreto es ";
        $movimiento = new Movimiento(null);
        $movimiento->recibirParametros("localhost", "6060");
        $correo = $sql->getCorreo($cliente->getNombre(), $cliente->getContraseñaAdministrador());
        $movimiento->enviarCorreo($correo, $titulo, $mensaje, $numeroAleatorio);  
        echo("
                <!DOCTYPE>
                <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <title>Modificar Contrseña</title>
                    </head>
                    <body>
                        <center>
                            <br>
                            <form method='post'>
                                <p>Introduce el número secreto enviado a tu correo: </p>
                                <input type='text' name='numeroSecreto'>
                                <p>Introduce la nueva contraseña: </p>
                                <input type='text' name='nuevaContraseña1'>
                                <br>
                                <p>Introduce de nuevo la nueva contraseña:</p>
                                <input type='text' name='nuevaContraseña2'>
                                <br>
                                <br>
                                <br>
                                <br>
                                <button typr='submit' name='confirmarContraseña'>Confirmar cambio</button>
                            </form>
                        </center>
                    </body>
                </html>
            ");   
    }
    else if(isset($_POST['confirmarContraseña']))
    {
        ob_end_clean();
        $sql = new MySQL();
        $cliente = unserialize($_SESSION['cliente']);
        $numeroAleatorio = unserialize($_SESSION['numeroAleatorio']);
        $numeroSecreto = $_REQUEST['numeroSecreto'];
        if($numeroAleatorio == $numeroSecreto and $_REQUEST['nuevaContraseña1'] == $_REQUEST['nuevaContraseña2'])
        {
            $sql->cambiarContraseña($cliente->getNombre(), $_REQUEST['nuevaContraseña1']);
            echo("<br><center>Contraseña modificada correctamente.</center>");
        }
        else
        {
            echo("<br><center>El número secreto es incorrecto.</center>");
        }
        unset($sql);
        unset($numeroSecreto);
        unset($numeroAleatorio);
        unset($cliente);
    }
?>