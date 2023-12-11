<?php
function isPendriveConnected() {
    $command = "lsblk -o NAME,FSTYPE,MOUNTPOINT,LABEL,SIZE,MODEL,VENDOR";
    exec($command, $output, $returnCode);

    foreach ($output as $line) {
        if (strpos($line, 'USB DISK') !== false || strpos($line, 'DISPOSITIVO') !== false) {
            $pendriveName = 'DISPOSITIVO';
            $pendriveDirectory = "/media/beatriz/{$pendriveName}";

            if (file_exists($pendriveDirectory) && is_dir($pendriveDirectory)) {
                $files = scandir($pendriveDirectory);

                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'pem') {
                        return "{$pendriveDirectory}/{$file}";
                    }
                }

                return false;
            } else {
                return false;
            }
        }
    }

    return false;
}

$pendrivePrivateKeyPath = isPendriveConnected();

if (!$pendrivePrivateKeyPath) {
    echo "Pendrive não detectado ou nenhum arquivo .pem encontrado. Autenticação falhou.";
    exit(1);
}

// Carrega a chave privada do pendrive
$privateKeyContent = file_get_contents($pendrivePrivateKeyPath);
$privateKey = openssl_pkey_get_private($privateKeyContent);

if ($privateKey === false) {
    echo "Falha ao carregar a chave privada do pendrive. Autenticação falhou.";
    exit(1);
} else {
    echo "Chave privada do pendrive carregada com sucesso.\n";
}

$publicKeysDirectory = '/var/www/html/seguranca/chaves_publicas/';
$publicKeyFiles = glob($publicKeysDirectory . '*.pem');

// Extrai a chave pública a partir da chave privada
$privateKeyDetails = openssl_pkey_get_details($privateKey);
$publicKey = $privateKeyDetails['key'];
// Remove espaços em branco e quebras de linha das chaves para comparação
$publicKey = str_replace(["\r", "\n", " "], '', $publicKey);

// Verifica se a chave pública do pendrive corresponde a alguma chave pública armazenada
foreach ($publicKeyFiles as $publicKeyFile) {
    $storedPublicKey = file_get_contents($publicKeyFile);
    $storedPublicKey = str_replace(["\r", "\n", " "], '', $storedPublicKey);

    // Verifica se a chave pública do pendrive corresponde à chave pública armazenada
    if ($publicKey === $storedPublicKey) {
        echo "Chave pública do pendrive corresponde a uma chave pública armazenada.\n";

        // Extrai o nome de usuário do arquivo da chave
        $fileName = pathinfo($publicKeyFile, PATHINFO_FILENAME);
        $underscorePos = strpos($fileName, '_');
        $userName = substr($fileName, 0, $underscorePos);

        // Carrega as informações de usuário do arquivo JSON
        $usersJsonPath = '/var/www/html/seguranca/batata.json'; // Substitua pelo caminho real
        $usersJson = file_get_contents($usersJsonPath);
        $usersData = json_decode($usersJson, true);

        // Verifica se o usuário existe no arquivo JSON
        if (isset($usersData[$userName])) {
            $cmd = "/usr/bin/php /var/www/html/seguranca/pagina_senha.php {$userName}";
            // Executa o script pagina_senha.php usando passthru
            passthru($cmd);
            exit;
        } else {
            echo "Usuário não encontrado no arquivo JSON. Autenticação falhou.\n";
            exit(1);
        }
    }
}

// Se o loop terminar e nenhuma correspondência for encontrada
echo "Chave pública do pendrive não corresponde a nenhuma chave pública armazenada. Autenticação falhou.\n";
exit(1);

?>
