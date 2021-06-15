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
                    <th>Nombre de la cámara</th>
                    <th></th>
                    <th>Estado</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    require_once("../Modelo/MySQL.php");
                    require_once("../Modelo/Camara.php");
                    require_once("../Modelo/Cliente.php");
                    $sql = new MySQL();
                    $cliente = unserialize($_SESSION['cliente']);
                    $camaras = $sql->obtenerCamars($cliente->getNombre(), $cliente->getContraseñaAdministrador());
                    for($i = 0; $i < count($camaras); ++$i)
                    {
                        $nombreCamara = $camaras[$i]->getNombre();
                        $estadoCamara = $camaras[$i]->getEstado();
                        echo("
                            <tr>
                                <td>$nombreCamara</td>
                                <td>
                                    <form method='post' method='post' target='_blank'>
                                        <button type='submit' name='renombrarCamara' value='$i'>Renombrar cámara</button>
                                    </form>
                                </td>
                                <td>$estadoCamara</td>
                                <td>
                                    <form method='post' method='post'>
                                        <button type='submit' name='estadoCamara' value='$i'>Modificar estado</button>
                                    </form>
                                </td>
                                <td>
                                    <form method='post' method='post' target='_blank'>
                                        <button type='submit' name='visualizarCamara' value='$i'>Visualizar cámara</button>
                                    </form>
                                </td>
                            </tr>
                        ");
                    }
                ?>
            </table>
        <br>
        </center>
    </body>
</html>
<?php
    if(isset($_POST['visualizarCamara']))
    {
        ob_end_clean();
        $i = $_POST['visualizarCamara'];
        $url = $camaras[$i]->getUrl();
        
        $rutaProyecto = $_SERVER['DOCUMENT_ROOT'];
        $rutaProyecto = str_replace("/", "\\", $rutaProyecto);
        $path = $rutaProyecto . "\PhpProject1\Streaming\stream.m3u8";
        $instruccion = "C:\SGCSR\\ffmpeg-4\bin\\ffmpeg.exe -v verbose  -i " . $url . " -vf scale=1920:1080  -vcodec libx264 -r 25 -b:v 1000000 -crf 31 -acodec aac  -sc_threshold 0 -f hls  -hls_time 5  -segment_time 5 -hls_list_size 5 " . $path;
        
        exec("taskkill /IM ffmpeg.exe /F");
        exec("del /S /Q " .  $rutaProyecto . "\PhpProject1\Streaming\*");
        
        $WshShell = new COM("WScript.Shell");
        $oExec = $WshShell->Run("cmd.exe /c " . $instruccion, 0, false);

        while(!file_exists("../Streaming/stream0.ts")){}
        
        echo("
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset=utf-8/>
                        <title>Visualizando camara</title>
                        <link href='https://unpkg.com/video.js/dist/video-js.css' rel='stylesheet'>
                        <script src='https://unpkg.com/video.js/dist/video.js'></script>
                        <script src='https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js'></script>
                    </head>
                    <body>
                        <video id='my_video_1' class='video-js vjs-fluid vjs-default-skin' controls preload='auto' data-setup='{}'>
                          <source src='http://localhost/PhpProject1/Streaming/stream.m3u8' type='application/x-mpegURL'>
                        </video>
                        <script>
                            var player = videojs('my_video_1');
                            player.play();
                        </script>
                    </body>
                </html>
            ");
    }
    if(isset($_POST['renombrarCamara']))
    {
        ob_end_clean();
        $i = $_POST['renombrarCamara'];
        echo("
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset=utf-8/>
                        <title>Renombrar cámara</title>
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
                        <form method='post'>
                            <center>
                            <table>
                                <tr>
                                    <th>Nuevo nombre de la cámara: </th>
                                    <td>
                                        <input type='text' name='renombrarCamaraSubapartado'>
                                    </td>
                                </tr>
                            </table>
                            </center>
                            <center>
                                <br>
                                <button type='submit' name='botonRenombrarCamaraSubapartado' value='$i'>Renombrar cámara</button>
                            </center>
                        </form>
                    </body>
                </html>
            ");
    }
    if(isset($_POST['botonRenombrarCamaraSubapartado']))
    {
        ob_end_clean();
        $i = $_POST['botonRenombrarCamaraSubapartado'];
        $nombre = $_REQUEST['renombrarCamaraSubapartado'];
        $url = $camaras[$i]->getUrl();
        $sql->renombrarCamara($nombre, $cliente->getNombre(), $url, $cliente->getContraseñaAdministrador());
        echo(
                "
                <br>
                <center>
                    Cámara renombrada con éxito.
                </center>
                "
            );
    }
    if(isset($_POST['estadoCamara']))
    {
        $i = $_POST['estadoCamara'];
        $url = $camaras[$i]->getUrl();
        $sql->cambiarEstadoCamara($cliente->getNombre(), $url, $cliente->getContraseñaAdministrador());
        header("Refresh:0");
    }
    
?>