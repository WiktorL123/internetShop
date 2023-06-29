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
    <link rel="stylesheet" href="style.css">
<!--    <script>-->
<!--        alert("Uwaga ta strona wykorzystuje ciasteczka")-->
<!---->
<!--    </script>-->
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


    <div id="filter">filtruj produkty
    <form action="index.php" method="post">
        <label>Nazwa
            <input type="text" name="nazwa">
        </label><br><br>
        <label>kategoria
            <select name="kategoria">
               <option>podzespoły komputerowe</option>
               <option>peryferia gamingowe</option>
               <option>telefony i tablety</option>
               <option>laptopy</option>
               <option>konsole</option>

            </select>
        </label><br><br>
        <label>cena od</label>
        <input class="price"  type="number" name="cenaod"><br>
        <label></label>cena do
        <input  class="price"  type="number" name="cenado">
        <input type="submit" value="szukaj" name="szukaj" id="szukaj">
    </form><br>

</div>
    <div id="main">
        <h2> NASZE PRODUKTY <?php if(isset($_SESSION['zalogowany'])): ?>
        <p>Zalogowano jako: <?php echo $_SESSION['email']?>
            <?php endif; ?>
        </p>


        </h2>

<!--        <div class="produkt">-->
<!--            produkt-->
<!--            <div class="img"><img src="ZASOBY/lenovo-laptop-l5-r5-16gb-1tbssd-3070-w11,102366425657_3.jpg">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="produkt"><div class="img"><img src="ZASOBY/nintendo-switch-joy-con-v2-czerwono-niebieski,49661310945_3.jpg"></div></div>-->
<!--        <div class="produkt"><div class="img"><img src="ZASOBY/apple-iphone-14-pro-max-256gb-czarny,111517043641_3.jpg"></div></div>-->
<!--        <div class="produkt"><div class="img"><img src="ZASOBY/amd-procesor-amd-ryzen-5-5600-box,102674486713_3.jpg"></div></div>-->
<!--    </div>-->
        <?php

//        if(!isset($_SESSION['email'])) {
//            header("Location: zaloguj.php");
//            }


            echo '<div id="produkty">';
//        var_dump($_POST);
            $host = "localhost";
            $db_name = "sklep";
            $user = "root";
            $pass = "";
            try {
                $con = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "udalo sie";
            } catch (PDOException $e) {

                echo "błąd połączenia kod błedu: " . $e->getCode() . " " . $e->getMessage();
            }
            if (!isset($_POST['cenado']) || !isset($_POST['cenaod']) || !isset($_POST['nazwa'])) {

                try {
                    $query = " SELECT * FROM produkty
                                INNER JOIN oceny ON oceny.produkty_id_produktu=produkty.id_produktu
                                ORDER BY RAND() LIMIT 4";
                    $stmt = $con->query($query);


                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {

                        $imageData = $row['zdjecie'];
                        $produktid = $row['id_produktu'];
                        echo "<a href='product.php?id=$produktid'>" .
                            "<div class='produkt'>" .
                            $row['nazwa'] . " " .
                            $row['cena'] . "zl" . " ";
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) .
                            '" alt="Zdjęcie produktu">' . "</div>" . "</a>";


                    }

                } catch (PDOException $exception) {
                    echo "Coś poszło nie tak, kod błedu: " . $e->getCode();
                }


            }
            if (isset($_POST['szukaj'])) {
                $kategoria = $_POST['kategoria'];
                $nazwa = strtolower($_POST['nazwa']);
                $cenaod = $_POST['cenaod'];
                $cenado = $_POST['cenado'];


                try {
                    $query = "SELECT produkty.id_produktu, produkty.nazwa AS n, zdjecie, cena 
          FROM produkty 
          INNER JOIN kategoria ON kategoria.id_kategori = produkty.id_kategori 
          WHERE kategoria.nazwa = :kategoria ";

                    $params = array(':kategoria' => $kategoria);

                    if (isset($_POST['cenaod']) && $_POST['cenaod'] !== '') {
                        $query .= "AND cena >= :cenaod ";
                        $params[':cenaod'] = $_POST['cenaod'];
                    }

                    if (isset($_POST['cenado']) && $_POST['cenado'] !== '') {
                        $query .= "AND cena <= :cenado ";
                        $params[':cenado'] = $_POST['cenado'];
                    }
                    if ($nazwa != '') {
                        $query .= "AND produkty.nazwa LIKE :nazwa";
                        $params[':nazwa'] = '%' . $_POST['nazwa'] . '%';
                    }


                    $stmt = $con->prepare($query);
                    $stmt->execute($params);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<div class='produkt'>";
                        $produktid = $row['id_produktu'];
                        echo "<a href='product.php?id=$produktid'>";
                        echo $row['n'] . " ";
                        echo $row['cena'] . "zl" . " ";


                        $imageData = $row['zdjecie'];
                        echo "<div class='img'>" .
                            '<img src="data:image/jpeg;base64,' . base64_encode($imageData) .
                            '" alt="Zdjęcie produktu">' . "</div>";
                        echo "</div>";
                    }
                } catch (PDOException $e) {
                    echo "Coś poszło nie tak, kod błedu: " . $e->getCode() . " opis: " . $e->getMessage();

                }


                echo "<br>";
//            var_dump($_POST);
//            print_r($params);

            }
            echo "</div>";


            ?>




        </div>


    <footer>Komputex.com © s27439 wszystkie prawa zastrzeżone</footer>
</div>
</body>
</html>

