<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Verificação do Sistema</h1>";


echo "<h2>Teste de Include</h2>";
$configPath = __DIR__ . '/../includes/config.php';
echo "Caminho do config.php: " . $configPath . "<br>";
echo "Arquivo existe? " . (file_exists($configPath) ? "Sim" : "Não") . "<br>";

echo "<h2>Permissões de Arquivos</h2>";
echo "Permissões do config.php: " . decoct(fileperms($configPath) & 0777) . "<br>";
echo "Permissões da pasta public: " . decoct(fileperms(__DIR__) & 0777) . "<br>";


echo "<h2>Caminhos do Sistema</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";


echo "<h2>Teste de Conexão com Banco de Dados</h2>";
try {
    require_once $configPath;
    echo "Conexão com banco de dados estabelecida com sucesso!";
} catch (Exception $e) {
    echo "Erro na conexão: " . $e->getMessage();
}