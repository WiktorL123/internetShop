<?php
 	    $host="szuflandia";
            $db_name="s27439";
            $user="s27439";
            $pass="Wik.Lema";
try {
    $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     echo "udalo sie";
}
catch (PDOException $e){
    echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
}