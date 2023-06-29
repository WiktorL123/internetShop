<?php
$to= "wiktorlem1@wp.pl";
$subject = "Sukces";
$messages= "Wiadomość została pomyślnie wysłana z serwera lokalnego.";

if( mail($to, $subject, $messages) ) {
    echo "Wiadomość wysłana!";
} else {
    echo "Niepowodzenie!";
}