<?php
    //DataBase configuration
    $host = "127.0.0.1"; // =>localhost
    $dbname = "MP_CONNECT";
    $username = "postgres";
    $password = "123456";
    $port = "5432";


 // Intentar la conexión
 $conn = pg_connect("
 host=$host
 dbname=$dbname
 user=$username
 password=$password
 port=$port
");

// Verificar si la conexión fue exitosa
if (!$conn) {
 die("Connection error: " . pg_last_error());
} else {
 // Conexión exitosa
 echo "Conexión exitosa a la base de datos.";
}
?>