<?php
require 'includes/db.php';

$nombre = "Administrador";
$email = "admin@admin.com";
$password_plana = "admin"; 
$password_hash = password_hash($password_plana, PASSWORD_DEFAULT); 
$rol = "admin";

$check = $conn->query("SELECT id FROM users WHERE email = '$email'");

if($check->num_rows > 0) {
    $sql = "UPDATE users SET password = '$password_hash', rol = '$rol' WHERE email = '$email'";
    if($conn->query($sql)) {
        echo "Usuario admin actualizado correctamente.";
        
    } else {
        echo "Error actualizando: " . $conn->error;
    }
} else {
    $sql = "INSERT INTO users (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password_hash', '$rol')";
    if($conn->query($sql)) {
        echo "Usuario admin creado correctamente. <br>Email: admin@admin.com <br>Pass: admin";

        // El nombre del usuario de la base de datos -> TODO: CAMIAR A "nombre_user@nombre_dominio_cnt_db_en_prod_env
        $sql_grant = "GRANT ALL PRIVILEGES ON *.* TO '$nombre'@'soteira_db' WITH GRANT OPTION";
        
        if($conn->query($sql_grant)) {
            echo "<br>Privilegios de MySQL otorgados correctamente.";
            $conn->query("FLUSH PRIVILEGES");
        }
        else {
            echo "<br>Error al otorgar privilegios: " . $conn->error;
        }

    } else {
        echo "Error creando: " . $conn->error;
    }
}
?>