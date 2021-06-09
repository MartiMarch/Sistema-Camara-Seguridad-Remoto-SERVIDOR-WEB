<?php
class ControladorVisualizarVideo 
{
    private $video;
    
    public function __construct($video) {
        $this->video = $video;
    }
    
    public function makeVisible()
    {
        require_once("../Modelo/Video.php");
        $this->video->visualizar();
    }
}
