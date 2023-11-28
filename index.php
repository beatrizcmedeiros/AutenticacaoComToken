<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>

<?php
// Arquivo login.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validação básica
    if (empty($username) || empty($password)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Autenticação simulada (substitua por lógica real)
        $storedUsername = "usuario";
        $storedPasswordHash = password_hash("senha123", PASSWORD_DEFAULT);

        if ($username === $storedUsername && password_verify($password, $storedPasswordHash)) {
            echo "Login bem-sucedido!";
        } else {
            echo "Usuário ou senha incorretos.";
        }
    }
}
?>
