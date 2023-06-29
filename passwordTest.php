<?php
$plainPassword = 'zaq1@WSX';

// Generowanie zahaszowanego hasła
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Wyświetlanie zahaszowanego hasła (może być przechowywane w bazie danych)
echo 'Zahaszowane hasło: ' . $hashedPassword ;

// Weryfikacja hasła
if (password_verify($plainPassword, $hashedPassword)) {
    echo 'Hasło jest poprawne!';
} else {
    echo 'Hasło jest niepoprawne!';
}