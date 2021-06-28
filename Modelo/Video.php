<?php

class Video{
    private $id, $year, $day, $month, $seconds, $hour, $minutes;
    
    public function __construct($id, $year, $day, $month, $seconds, $hour, $minutes) {
        $this->id = $id;
        $this->year = $year;
        $this->day = $day;
        $this->month = $month;
        $this->seconds = $seconds;
        $this->hour = $hour;
        $this->minutes = $minutes;
    }
    
    function getId() {
        return $this->id;
    }

    function getYear() {
        return $this->year;
    }

    function getDay() {
        return $this->day;
    }

    function getMonth() {
        return $this->month;
    }

    function getSeconds() {
        return $this->seconds;
    }

    function getHour() {
        return $this->hour;
    }

    function getMinutes() {
        return $this->minutes;
    }
    
    function visualizar()
    {
        if(!file_exists("../Multimedia/" . $this->id . "mp4"))
        {
            $rutaServidorWeb = $_SERVER['DOCUMENT_ROOT'] . "/PhpProject1/Multimedia";
            $rutaServidorWeb = str_replace('/', '\\', $rutaServidorWeb);
            $rutaVideo = 'C:/SGCSR/' . $this->id . ".mp4";
            $rutaVideo = str_replace('/', '\\', $rutaVideo);
            exec('COPY "' . $rutaVideo . '" "' . $rutaServidorWeb . '"');
        }
        $nombreArchio = "../Multimedia/" . $this->id . ".mp4";
        echo("
                <center>
                    <video src='$nombreArchio'  width='1280'  height='720' controls>
                        <p>Tu navegador no soporta HTML5 video. Aquí está el <a href='rabbit320.webm'>enlace del video</a>.</p>
                     </video>
                </center>
            ");
    }
}
