<?php
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
    {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        exit;
    }
?>
<!DOCTYPE>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Iniciar Sesion</title>
    </head>
    <body>
        <form method="post" method="post">
            <center>
            <br>
            <table>
                <tr>
                    <td>
                        Nombre:
                    </td>
                    <td>
                        <input type="text" name="text_iniciarSesion_nombre">
                    </td>
                </tr>
                <tr>
                    <td>
                        Contraseña 
                    </td>
                    <td>
                        <input type="password" name="text_iniciarSesion_contraseña">
                    </td>
                </tr>
            </table>
            <br>
            <input type="submit" value="Iniciar sesion" name="iniciarSesionSubmit">                
            </center>
        </form>
    </body>
</html>
<?php
    require_once("Modelo/MySQL.php");
    require_once("Modelo/Cliente.php");
    require_once("Controlador/ControladorPaginaPrincipal.php");
    if(isset($_POST["iniciarSesionSubmit"]))
    {
        $sql = new MySQL();
        if($sql->validarUsuario($_POST["text_iniciarSesion_nombre"], $_POST["text_iniciarSesion_contraseña"]))
        {
            $cliente = new Cliente();
            $cliente->setNombre($_POST["text_iniciarSesion_nombre"]);
            $cliente->setContraseña($_POST["text_iniciarSesion_contraseña"]);
            $cliente->recibirContraseña("localhost", "6050");
            session_start();
            $_SESSION['cliente'] = serialize($cliente);
            header('Location: Vista/PaginaPrincipal.php');
        }
        else
        {
            echo "<script type='text/javascript'>alert('Contraseña incorrecta.');window.location.href='index.php';</script>";
        }
        unset($cliente);
        unset($sql);
    }
?>