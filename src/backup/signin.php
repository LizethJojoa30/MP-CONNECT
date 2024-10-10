<?php
include("../../config/database.php");

session_start();

// Redirigir al usuario si ya está autenticado
if (isset($_SESSION["id_user"])) {
    header("Location: ../../paginainicio.php");
    exit;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y validar datos de entrada
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$email || !$password) {
        header("refresh:0;url=../../formulario inicio sesion.html");
        exit;
    }

    // Preparar la consulta para evitar inyecciones SQL
    $sql = "SELECT * FROM Usuarios WHERE email = $1";
    $result = pg_query_params($conn, $sql, [$email]);

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        
        // Verificar la contraseña usando password_verify
        if (password_verify($password, $row['password'])) {
            // Regenerar el ID de sesión para mayor seguridad
            session_regenerate_id(true);

            // Establecer variables de sesión
            $_SESSION['id_user'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_email'] = $row['email'];

            // Redirigir al usuario a la página de inicio
            header("Location: ../../paginainicio.php");
            exit;
        } else {
            header("Location: ../../index.html?error=credenciales_invalidas");
            exit;
        }
    } else {
        header("Location: ../../index.html?error=credenciales_invalidas");
        exit;
    }
}

// Cerrar la conexión a la base de datos
pg_close($conn);
?>
