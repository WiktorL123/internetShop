<?php
$host="localhost";
$db_name="sklep";
$user="root";
$pass="";
try {
    $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     echo "udalo sie";
}
catch (PDOException $e){
    echo "błąd : ".$e->getMessage()."kod błędu: ".$e->getCode();
}