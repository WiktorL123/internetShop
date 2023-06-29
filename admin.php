<?php
session_start()
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sklep komputerowy Komputex.com</title>
<style>






</style>
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
<div id="container">
<header>
    <div id="logo">
    <h1 style="text-align: center">Komputex.com</h1>
        <?php if (isset($_SESSION['zalogowany'])) : ?>
<!--            <p>Jesteś zalogowany jako: --><!--</p>-->
            <a href="logut.php"><span id="logout">Wyloguj</span></a>
        <?php endif; ?>
        <a href="rejestracja.php">  <span id="rejestracja">rejestracja</span></a>
        <a href="zaloguj.php"><span id="logowanie">logowanie</span></a>

    </div>
<!--    <div id="menu">-->
<!--<!--                <div class="option"><a href="podzespoly.php">Podzespoły komputerowe</a></div>-->-->
<!--<!--                <div class="option"><a href="laptopy.php">Laptopy</a> </div>-->-->
<!--<!--                <div class="option"><a href="smartfony i talety.php">Smartfony i tablety</a></div>-->-->
<!--<!--                <div class="option"><a href="konsole.php">Konsole</a</div>-->-->
<!--<!--                <div class="option"><a href="peryferia.php">peryferia gamingowe</a></div>-->-->
<!--    </div>-->
</header>


<!--    <div id="filter">filtruj produkty-->
<!--    <form action="" method="post">-->
<!--        <label>Nazwa-->
<!--            <input type="text" name="nazwa">-->
<!--        </label><br><br>-->
<!--        <label>kategoria-->
<!--            <select name="kategoria">-->
<!--               <option>podzespoły komputerowe</option>-->
<!--               <option>peryferia gamingowe</option>-->
<!--               <option>telefony i tablety</option>-->
<!--               <option>laptopy</option>-->
<!--               <option>konsole</option>-->
<!---->
<!--            </select>-->
<!--        </label><br><br>-->
<!--        <label>cena od</label>-->
<!--        <input class="price"  type="number" name="cenaod"><br>-->
<!--        <label></label>cena do-->
<!--        <input  class="price"  type="number" name="cenado">-->
<!--        <input type="submit" value="szukaj" name="szukaj" id="szukaj">-->
<!--    </form><br>-->
<!---->
<!--</div>-->
    <div id="main">
        <h2> Panel Administratora <?php if(isset($_SESSION['zalogowany'])): ?>
        <p>Zalogowano jako: <?php echo $_SESSION['email']?>
            <?php endif; ?>
        </p>
        </h2>

        <?php

        if(!isset($_SESSION['zalogowany'])){
            header('location: zaloguj.php');
        }
        function deleteCategory($id)
        {
            $host="localhost";
            $db_name="sklep";
            $user="root";
            $pass="";
            try {
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }
            catch (PDOException $e){
                echo "błąd połączenia kod błedu: ".$e->getCode()." ".$e->getMessage();
            }

            try {
                $query="DELETE FROM kategoria where id_kategori=:id";
                $stmt=$db->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            }
            catch (PDOException $e){
                echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
            }

        }


        function deleteProduct($id){
            $host="localhost";
            $db_name="sklep";
            $user="root";
            $pass="";
            try {
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }
            catch (PDOException $e){
                echo "błąd połączenia kod błedu: ".$e->getCode()." ".$e->getMessage();
            }

            try {
                $query="UPDATE produkty set ilosc=ilosc-1 where id_produktu=:id";
                $stmt=$db->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            }
            catch (PDOException $e) {
                echo "błąd  ". $e->getCode() ."kod błedu: " . $e->getMessage();
            }
            try {
                $query2 = "SELECT * FROM produkty where id_produktu=:id";
                $results=$db->prepare($query2);
                $results->bindValue(':id', $id);
                $results->execute();
            foreach ($results as $result)
                    $ilosc=$result['ilosc'];
                    if($ilosc<0){
                        $query3="DELETE FROM produkty where id_produktu=:id";
                        $statement=$db->prepare($query3);
                        $statement->bindValue(':id', $id);
                        $statement->execute();
                    }
            }
            catch (PDOException $e){
                echo "błąd  ". $e->getCode() ."kod błedu: " . $e->getMessage();
            }
        }
        function displayProducts()
        {
            require_once "connect.php";
            try {


                $query = "SELECT * FROM produkty";
                $results = $db->query($query);
//            echo "<table><tr>";
                foreach ($results as $result) {
                      $id=$result['id_produktu'];
                    echo $result['nazwa'] . ", " .
                        $result['ilosc'] . "sztuk" ."
<form action='admin.php' method='post' ><input type='hidden' name='produktId' value='$id'><button type='submit' name='usunprodukt'>Usuń</button></form>
             <form action='admin.php' method='post'><input type='hidden' name='produktId' value='$id'><input type='number' name='ilosc' style='width: 25px'> <button type='submit' name='dodaj'>dodaj</button>  </form>";
                }
//            echo "</table></tr>";
            }
            catch (PDOException $e){
                echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
            }
        }
        function displayCategories()
        {
            $host="localhost";
            $db_name="sklep";
            $user="root";
            $pass="";
            try {
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }
            catch (PDOException $e){
                echo "błąd  ".$e->getMessage()."kod błedu: ".$e->getCode();
            }


            try {


                $query = "SELECT * FROM kategoria";
                $results = $db->query($query);
                foreach ($results as $result) {
                        $id=$result['id_kategori'];
                        echo $result['id_kategori'] . ", " .
                        $result['nazwa'] ." ".
                            "<form action='admin.php' method='post' ><input type='hidden' name='kategoriaId' value='$id'><button type='submit' name='usun'>Usuń</button></form>";
                }

            }
            catch (PDOException $e){
                echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
            }
        }
        function dodajSztuke($id, $ilosc){
            $host="localhost";
            $db_name="sklep";
            $user="root";
            $pass="";
            try {
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }
            catch (PDOException $e){
                echo "błąd połączenia kod błedu: ".$e->getCode()." ".$e->getMessage();
            }
            $query="UPDATE produkty set ilosc=ilosc+:ilosc where id_produktu=:id";
            $result=$db->prepare($query);
            $result->bindValue(':ilosc', $ilosc);
            $result->bindValue(':id', $id);
            $result->execute();


        }



        function addCategories($kategoria)
        {


            try {
                $host="localhost";
                $db_name="sklep";
                $user="root";
                $pass="";
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




                $query = "INSERT INTO kategoria values (null, :kategoriaNazwa)";
                $results = $db->prepare($query);
                $results->bindParam(':kategoriaNazwa', $kategoria);
                $results->execute();
                echo "dodano kategorie";
            }catch (PDOException $e){
                echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
            }
            }


        function addProduct($nazwa, $opis, $cena, $ilosc, $kategoriaProdukt, $zdjecie )
        {
            try {
                $host="localhost";
                $db_name="sklep";
                $user="root";
                $pass="";
                $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




                $query = "INSERT INTO produkty values (null, :nazwa, :opis, :cena, :ilosc, :kategoriaProdukt, :zdjecie )";

                $results = $db->prepare($query);
                $results->bindParam(':nazwa', $nazwa);
                $results->bindParam(':opis', $opis);
                $results->bindParam(':cena', $cena);
                $results->bindParam(':ilosc', $ilosc);
                $results->bindParam(':kategoriaProdukt', $kategoriaProdukt);
                $results->bindParam(':zdjecie', $zdjecie);
                $results->execute();
                echo "dodano kategorie";
            }catch (PDOException $e){
                echo "Błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
            }
        }



        echo "<br>";
        echo "<h1>Produkty:</h1>";
        echo "<p>doodaj produkt</p>";
        echo "1-podzespoły komputerowe<br> ";
        echo "2-peryferia gamingowe<br>";
        echo "3-telefony i tablety<br>";
        echo "4-laptopy<br>";
        echo "5-konsole<br>";
        echo "<form action='admin.php' method='post'>nazwa produktu<input name='produktNazwa'><br>
           opis produktu <textarea name='opis'></textarea><br>
           kategoria<select name='produktKategoria'>
          podzespoły komputerowe<br>
           <option>1</option>
           <option>2</option>
           <option>3</option>
           <option>4</option>
           <option>5</option>
</select><br>
            ilość<input type='number' name='ilosc'><br>
            cena<input type='number' name='cena'><br>
            zdjecie<input type='file' name='zdjecie'><br>
            <input type='submit' name='dodajProdukt'></form>";



        if(isset($_POST['dodajProdukt'])) {
            $cena=$_POST['cena'];
            $zdjecie=$_POST['zdjecie'];
            $ilosc=$_POST['ilosc'];
            $nazwa = $_POST['produktNazwa'];
            $opis = $_POST['opis'];
            $kategoriaProdukt = $_POST['produktKategoria'];
                addProduct($nazwa, $opis, $cena, $ilosc, $kategoriaProdukt, $zdjecie );
}
        displayProducts();
        echo "<br>";
        echo "<h1>Kategorie:</h1>";
        echo "<p>dodaj kategorie</p>";
        echo "<form action='admin.php' method='post'><input name='kategoriaNazwa'><input type='submit'></form>";
        displayCategories();

        if(isset($_POST['kategoriaNazwa'])) {
            $kategoriaNazwa=$_POST['kategoriaNazwa'];
            addCategories($kategoriaNazwa);
        }
        var_dump($_POST);
        if(isset($_POST['usun'])){
            $id=$_POST['kategoriaId'];
            deleteCategory($id);
        }
        if(isset($_POST['usunprodukt'])){
            $id=$_POST['produktId'];
            deleteProduct($id);
        }
        if(isset($_POST['dodaj'])){
            $ilosc=$_POST['ilosc'];
            $id=$_POST['produktId'];
            dodajSztuke($id, $ilosc);
        }




        ?>


        </div>



</div>
</body>
</html>

