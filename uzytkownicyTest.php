<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sklep";

// Tworzenie połączenia z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
die("Nie udało się połączyć z bazą danych: " . $conn->connect_error);
}

// Wykonanie zapytania w pętli dla ID od 2 do 50
for ($id = 2; $id <= 50; $id++) {

$sql = "UPDATE uzytkownicy SET is_admin = false WHERE id = $id";

// Wykonanie zapytania
if ($conn->query($sql) === TRUE) {
echo "Zaktualizowano rekord o ID: $id<br>";
} else {
echo "Błąd podczas aktualizacji rekordu o ID: $id - " . $conn->error . "<br>";
}
}

// Zamykanie połączenia
$conn->close();