<?php

class Console{
    static function writeln($message){
        echo "<br>";
        echo $message;
        echo "<br>";
    }
    
    static function vardump($message){
        echo "<br>";
        var_dump($message);
        echo "<br>";
    }
}
?>
