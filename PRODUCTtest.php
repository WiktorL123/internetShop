<?php
$host="localhost";
$db_name="sklep";
$user="root";
$pass="";
try {


    $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "select imie, tresc from uzytkownicy
                    inner join opinie  on uzytkownicy.id = opinie.uzytkownicy_id
                     inner join produkty  on opinie.produkty_id_produktu = produkty.id_produktu";
    $statement = $db->query($query);
   $row=$statement->fetchAll(PDO::FETCH_ASSOC);

   echo "<table>";
   echo "<tr>";

    foreach ($row as $value){
        echo "<td>".$value['imie']."</td>";
        echo "<td>".$value['tresc']."</td>";
    }
    echo "</table>";
    echo "</tr>";


}
catch (PDOException $e){
    $e->getCode();
}