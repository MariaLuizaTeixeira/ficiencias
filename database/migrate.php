<?php

$pdo = require __DIR__ . '/config.php';

// tabela de controle
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id SERIAL PRIMARY KEY,
        migration VARCHAR(255),
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// migrations já executadas
$stmt = $pdo->query("SELECT migration FROM migrations");
$executadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

// caminho correto da pasta migrations
$path = __DIR__ . '/migrations';

$arquivos = scandir($path);
sort($arquivos);

foreach ($arquivos as $arquivo) {
    if (!str_ends_with($arquivo, '.sql')) continue;
    if (in_array($arquivo, $executadas)) continue;

    $sql = file_get_contents($path . "/$arquivo");

    $comandos = array_filter(array_map('trim', explode(';', $sql)));

    $pdo->beginTransaction();

    try {
        foreach ($comandos as $comando) {
            if (!empty($comando)) {
                $pdo->exec($comando);
            }
        }

        $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$arquivo]);

        $pdo->commit();

        echo "Rodou: $arquivo\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro em $arquivo: " . $e->getMessage() . "\n";
    }
}