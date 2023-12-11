<?php
echo "Script pagina_senha.php iniciado.\n"; 

// Verifica se o nome do usuário foi passado como argumento
if ($argc < 2) {
    echo "Uso: php pagina_senha.php <nome_do_usuario>\n";
    exit(1);
}

$nomeUsuario = $argv[1];

// Mensagem de depuração
echo "Nome do usuário: $nomeUsuario\n";

// Carrega as senhas do arquivo JSON
$senhas = json_decode(file_get_contents('/var/www/html/seguranca/batata.json'), true);

// Mensagem de depuração
echo "Senhas do arquivo JSON: " . print_r($senhas, true) . "\n";

// Verifica se existe uma senha para o usuário fornecido
if (isset($senhas[$nomeUsuario])) {
    // Solicita a senha ao usuário
    echo "Digite a senha para o usuário {$nomeUsuario}: ";
    $senhaDigitada = trim(fgets(STDIN));

    // Verifica se a senha está correta
    if ($senhaDigitada === $senhas[$nomeUsuario]) {
        echo "Autenticação bem-sucedida. Bem-vindo, {$nomeUsuario}!\n";
        exit(0);
    } else {
        echo "Senha incorreta. Autenticação falhou.\n";
        exit(1);
    }
} else {
    echo "Usuário não encontrado. Autenticação falhou.\n";
    exit(1);
}
?>
