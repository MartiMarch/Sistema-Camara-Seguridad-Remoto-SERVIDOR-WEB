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
                    <th>Fecha</th>
                    <th>dd/mm/yyyy</th>
                    <th>hh:mm:ss</th>
                    <th></th>
                </tr>
                <?php
                    require_once("../Modelo/MySQL.php");
                    require_once("../Modelo/Video.php");
                    $sql = new MySQL();
                    $videos = $sql->obtenerVideos();
                    for($i = 0; $i < count($videos); ++$i)
                    {
                        echo("<tr>");
                            echo("<td>");
                                echo($i);
                            echo("</td>");
                            echo("<td>");
                                echo($videos[$i]->getDay() . "/" . $videos[$i]->getMonth() . "/" . $videos[$i]->getYear());
                            echo("</td>");
                            echo("<td>");
                                echo($videos[$i]->getHour() . ":" . $videos[$i]->getMinutes() . ":" . $videos[$i]->getSeconds());
                            echo("</td>");
                            echo("<td>");
                                echo("
                                        <form method='post' method='post' target='_blank'>
                                            <button type='submit' name='visualizar' value='$i'>Visualizar Vídeo</button>
                                        </form>
                                    ");
                            echo("</td>");
                        echo("</tr>");
                    }
                    unset($sql);
                ?>
            </table>
        <br>
        </center>
    </body>
</html>
<?php
    if(isset($_POST['visualizar']))
    {
        ob_end_clean();
        $i = $_POST['visualizar'];
        require_once("../Controlador/ControladorVisualizarVideo.php");
        $visualizarVideo = new ControladorVisualizarVideo($videos[$i]);
        $visualizarVideo->makeVisible();
    }
?>