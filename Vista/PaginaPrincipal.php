<?php
    $rutaProyecto = $_SERVER['DOCUMENT_ROOT'];
    $rutaProyecto = str_replace("/", "\\", $rutaProyecto);
    exec("taskkill /IM ffmpeg.exe /F");
    exec("del /S /Q " .  $rutaProyecto . "\PhpProject1\Streaming\*");
    session_start();
    if(!isset($_SESSION['cliente']))
    {
        header('Location: ' . $_SESSION['defaultURL']);
    }
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
        <title>Pagina princiapal</title>
    </head>
    <body>
        <center>
            <br>
            <table>
                <tr>
                    <td>
                        <input type="button" name="ppAjustes" onclick="location.href = 'Videos.php';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Videos</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" name="ppVideos" onclick="location.href = 'Camaras.php';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Camaras</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" name="ppAjustes" onclick="location.href = 'Ajustes.php';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ajustes</p>
                    </td>
                </tr>
            </table>
            <br>       
        </center>
    </body>
</html>