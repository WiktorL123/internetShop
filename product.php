<?php
session_start();

// 5. Pobierz dane produktu na podstawie identyfikatora z parametru URL
function showRates(mixed $id)
{
    $host = "localhost";
    $db_name = "sklep";
    $user = "root";
    $pass = "";
    try {
        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "udalo sie";

    $query="SELECT uzytkownicy.imie, avg(ocena) as ocena, produkty.nazwa, produkty.id_produktu
            FROM uzytkownicy
            INNER JOIN oceny ON uzytkownicy.id = oceny.uzytkownicy_id
            INNER JOIN produkty ON oceny.produkty_id_produktu = produkty.id_produktu
            where id_produktu=:id
            GROUP BY uzytkownicy.imie, produkty.nazwa, produkty.id_produktu ";
    $statement=$db->prepare($query);
    $statement->bindParam(':id', $id);
    $statement->execute();
    foreach ($statement as $item){
        echo $item['imie']." ".$item['ocena'];
    }

    }
    catch (PDOException $e){
        echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }
            }

function addRate(mixed $id, mixed $idUser, mixed $ocena)
{
    $host="localhost";
    $db_name="sklep";
    $user="root";
    $pass="";
    try {
        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // echo "udalo sie";
        $query="INSERT INTO oceny VALUES (null, :ocena, :id, :idUser )";
        $statement=$db->prepare($query);
        $statement->bindParam(':ocena', $ocena);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':idUser', $idUser);
        $statement->execute();

    }catch (PDOException $e){
        echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }
}

function displaySimilarProduct(mixed $kategoria)
{
    $host="localhost";
    $db_name="sklep";
    $user="root";
    $pass="";
    try {
        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "udalo sie";
        $query="SELECT * FROM produkty where id_kategori=:kategoria";
        $statement=$db->prepare($query);
        $statement->bindParam(':kategoria', $kategoria);
        $statement->execute();
        foreach ($statement as $item){
            $imageData = $item['zdjecie'];
            $produktid=$item['id_produktu'];
            echo "<a href='product.php?id=$produktid'>" .
                "<div class='produkt'>" ;
            echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) .
                '" alt="Zdjęcie produktu">' . "</div>" . "</a>";
        }

    }catch (PDOException $e){
        echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }
}

function addOpinion(mixed $productId, mixed $idUser, mixed $opinia)
{

    $host="localhost";
    $db_name="sklep";
    $user="root";
    $pass="";
    try {
        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     echo "udalo sie";
        $query="INSERT INTO opinie values (null, :idProdukt, :opinia, :idUser)";

        $result=$db->prepare($query);
        $result->bindParam(':idProdukt', $productId);
        $result->bindParam(':opinia', $opinia);
        $result->bindParam(':idUser', $idUser);
        $udaloSie=$result->execute();
        if($udaloSie)
            echo "dodano opinie";

    }
    catch (PDOException $e){
        echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }



}

function displayProduct($id)
{
    require_once "connect.php";

    try {


        $query = "SELECT * FROM produkty WHERE id_produktu = :id";
        $result = $db->prepare($query);
        $result->bindParam(':id', $id);
        $result->execute();
        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $productName = $row['nazwa'];
            $productPrice = $row['cena'];
            $productImage = $row['zdjecie'];
            $_SESSION['productName']=$productName;
            $_SESSION['kategoria']=$row['id_kategori'];
//        echo "<img src='$productImage' alt='$productName'>";
            echo "<h3>$productName</h3>";
            echo "<p>Cena: $productPrice</p>";
            echo '<img src="data:image/jpeg;base64,' . base64_encode($productImage) .
                '" alt="Zdjęcie produktu">' . "</div>" . "</a>";
        }
    }
    catch (PDOException $e){
        echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }

}

function showOpinions($id){



    $host="localhost";
    $db_name="sklep";
    $user="root";
    $pass="";
    try {
        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query="select imie, tresc from uzytkownicy
                    inner join opinie  on uzytkownicy.id = opinie.uzytkownicy_id
                     inner join produkty  on opinie.produkty_id_produktu = produkty.id_produktu where id_produktu=:id";
        $statement=$db->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $row=$statement->fetchAll(PDO::FETCH_ASSOC);
        echo "<table>";
        echo "<tr>";
        foreach ($row as $value){

            echo "<td><strong>".$value['imie']."</strong></td>".
                "<td>". $value['tresc']."</td>";
        }
        echo "</tr>";
        echo "</table>";
    }
    catch (PDOException $e){
        echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
    }






}



if (isset($_GET['id']))
{
    $productId = $_GET['id'];

echo "<div class='mainProdukt'>";
    displayProduct($productId);
    showOpinions($productId);
    showRates($productId);

    echo "</div>";
    echo "<h2>Zobacz inne produkty z tej kategorii</h2>";
    displaySimilarProduct($_SESSION['kategoria']);
    echo "<br>";

//    echo $_SESSION['id'];
    echo "<h2>Wyraź opinie</h2>";
    echo " <form method='post' action=''> <textarea name='opinia'></textarea><input type='submit' value='prześlij' name='wyslij'></form>";
    echo "<h1>oceń produkt</h1>";
    echo " <form method='post' action=''><select name='ocena'>
 <option>1</option>
 <option>2</option>
 <option>3</option>
 <option>4</option>
 <option>5</option>
 </select><input type='submit' value='prześlij' name='ocen'></form>";

//    var_dump($_POST);

    $idUser=$_SESSION['id'];
    if(isset($_POST['wyslij'])){
        $opinia=$_POST['opinia'];
//        echo $opinia."<br>";
        addOpinion($productId, $idUser, $opinia);
    }
    if(isset($_POST['ocen'])){
        $ocena=$_POST['ocena'];
        addRate($productId, $idUser, $ocena);
    }



}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $_SESSION['productName']?></title>
<style>
    .mainProdukt img{
        width: 250px;
        height: 250px;
    }
    .produkt{

        float: left;
    }
    img{
        width: 100px;
        height: 100px;
    }
    body{
        background-image: url("ZASOBY/v748-toon-103.jpg");
    }
</style>
</head>
<body>
<BR>
<form action="" method="post">





</form>
</body>
</html>

