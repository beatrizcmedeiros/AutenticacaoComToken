<?php

function obterSenha() {
    echo "Digite sua senha: ";
    $senha = trim(fgets(STDIN)); // Obtém a senha sem mostrar na tela
    return $senha;
}

function verificarSenha($senhaDigitada, $senhaArmazenada) {
    return hash('sha256', $senhaDigitada) === $senhaArmazenada;
}

function verificarPendrive($chavePendrive) {
    $caminhoPendrive = '/path/to/pendrive/key.txt';
    
    try {
        $chaveArmazenada = trim(file_get_contents($caminhoPendrive));
        return $chavePendrive === $chaveArmazenada;
    } catch (Exception $e) {
        return false;
    }
}

function autenticar() {
    $senhaDigitada = obterSenha();

    // Substitua 'hash_da_senha' pela senha do usuário armazenada em seu sistema
    $senhaHash = 'hash_da_senha';

    if (verificarSenha($senhaDigitada, $senhaHash)) {
        echo "Insira o pendrive e pressione Enter: ";
        $chavePendrive = trim(fgets(STDIN));

        if (verificarPendrive($chavePendrive)) {
            echo "Autenticação bem-sucedida. Bem-vindo!\n";
        } else {
            echo "Chave do pendrive incorreta. Autenticação falhou.\n";
        }
    } else {
        echo "Senha incorreta. Autenticação falhou.\n";
    }
}

autenticar();

?>
