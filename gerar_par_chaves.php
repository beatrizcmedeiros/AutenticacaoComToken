<?php

// Função para gerar um par de chaves RSA e salvar as chaves públicas e privadas
function generateKeyPair($userName) {
    // Configurações para a geração da chave
    $config = [
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ];

    // Gera um novo par de chaves
    $res = openssl_pkey_new($config);

    // Obtém a chave privada
    openssl_pkey_export($res, $privateKey);

    // Obtém a chave pública
    $publicKey = openssl_pkey_get_details($res)['key'];

    // Salva as chaves pública e privada em arquivos específicos para cada usuário
    file_put_contents("/var/www/html/seguranca/chaves_publicas/{$userName}_public_key.pem", $publicKey);
    file_put_contents("/var/www/html/seguranca/chaves_privadas/{$userName}_private_key.pem", $privateKey);

    echo "Chaves geradas com sucesso para {$userName}.\n";
}

// Gera pares de chaves para cinco usuários
$usernames = ['user1', 'user2', 'user3', 'user4', 'user5'];

foreach ($usernames as $username) {
    generateKeyPair($username);

    echo "Chaves geradas com sucesso para {$username}.\n";
}

?>
