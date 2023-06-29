<?php
session_start();

// 5. Pobierz dane produktu na podstawie identyfikatora z parametru URL
if (isset($_GET['id']))
{
    $productId = $_GET['id'];
    function addOpinion(mixed $productId, mixed $idUser, mixed $opinia)
    {

            $host="szuflandia";
            $db_name="s27439";
            $user="s27439";
            $pass="Wik.Lema";
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



	    $host="szuflandia";
            $db_name="s27439";
            $user="s27439";
            $pass="Wik.Lema";

        try {
            $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query="select imie, tresc  from uzytkownicy
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


    function showGrades($id)
    {

        $host="szuflandia";
        $db_name="s27439";
        $user="s27439";
        $pass="Wik.Lema";
        try {


        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query="select imie, ocena from uzytkownicy
                    inner join oceny on produkty.id_produktu = oceny.produkty_id_produktu
                     inner join produkty  on opinie.produkty_id_produktu = produkty.id_produktu where id_produktu=:id";
        $statement=$db->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $row=$statement->fetchAll(PDO::FETCH_ASSOC);
        echo "<table>";
        echo "<tr>";
        foreach ($row as $value){

            echo "<td><strong>".$value['imie']."</strong></td>".
                "<td>". $value['ocena']."</td>";
        }
        echo "</tr>";
        echo "</table>";
    }
catch (PDOException $e){
    echo "błąd: ".$e->getMessage()."kod błędu: ".$e->getCode();
}
    }




    displayProduct($productId);
    showOpinions($productId);
//    echo $_SESSION['id'];
    echo " <form method='post' action=''> <textarea name='opinia'></textarea><input type='submit' value='prześlij' name='wyslij'></form>";

    $idUser=$_SESSION['id'];
    if(isset($_POST['wyslij'])){
        $opinia=$_POST['opinia'];
        echo $opinia."<br>";
        addOpinion($productId, $idUser, $opinia);
    }

  //  showGrades($productId);
    //echo "<form action='' method='post'><input type='number' name='ocena'><input type='submit' name='ocen'></form>";
    var_dump($_POST);



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
    img{
        width: 250px;
        height: 250px;
    }
</style>
</head>
<body>
<BR>
<form action="" method="post">
<h1>oceń produkt</h1>




</form>
</body>
</html>

