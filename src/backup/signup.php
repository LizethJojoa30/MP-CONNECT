<?php
include("../../config/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y validar los datos de entrada
    $nombre = $_POST['nombre'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $password = $_POST['contraseña'] ?? null;
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;

    // Validar que no falten campos obligatorios
    if (!$nombre || !$email || !$telefono || !$password || !$tipo_usuario) {
        echo "<script>alert('Todos los campos son obligatorios.');</script>";
        header("refresh:0;url=../../formulario registro.html");
        exit;
    }

    // Validar formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo electrónico no es válido.');</script>";
        header("refresh:0;url=../../formulario registro.html");
        exit;
    }

    // Encriptar la contraseña usando password_hash (más seguro que md5)
    $enc_pass = password_hash($password, PASSWORD_BCRYPT);

    // Validar si el correo ya existe en la base de datos
    $sql_validate_email = "SELECT * FROM Usuarios WHERE email = $1";
    $result = pg_query_params($conn, $sql_validate_email, [$email]);
    $total = pg_num_rows($result);

    if ($total > 0) {
        echo "<script>alert('USUARIO YA EXISTE');</script>";
        header("refresh:0;url=../../formulario registro.html");
        exit;
    } else {
        // Preparar la consulta de inserción de forma segura para evitar inyecciones SQL
        $sql = "INSERT INTO Usuarios (nombre, email, telefono, password, tipo_usuario) 
                VALUES ($1, $2, $3, $4, $5)";

        $params = [$nombre, $email, $telefono, $enc_pass, $tipo_usuario];
        $ans = pg_query_params($conn, $sql, $params);

        if ($ans) {
            echo "<script>alert('REGISTRO EXITOSO');</script>";
            header("refresh:0;url=../../index.html");
            exit;
        } else {
            echo "Error en la inserción: " . pg_last_error($conn);
        }
    }

    // Cerrar la conexión
    pg_close($conn);
}
?>
