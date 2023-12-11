<?php

// Verifica se foi fornecido um argumento na linha de comando
if ($argc < 2) {
    echo "Uso: php pagina_senha.php <nome_do_usuario>\n";
    exit(1);
}

// Pega o primeiro argumento passado na linha de comando
$nomeUsuario = $argv[1];

// Carrega as senhas do arquivo JSON
$senhas = json_decode(file_get_contents('/var/www/html/seguranca/batata.json'), true);

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
