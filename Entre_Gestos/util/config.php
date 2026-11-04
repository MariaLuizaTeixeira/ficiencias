<?php

//Mostrar erros do PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Configurar essas variáveis de acordo com o seu ambiente
define("DB_HOST", "localhost");
define("DB_NAME", "entregestos");
define("DB_USER", "root");
define("DB_PASSWORD", "");   

define("AMB_DEV", true);

define("UPLOAD_PATH", __DIR__ . "/../public/");


// Calcula a URL-base automaticamente a partir da pasta do projeto dentro do servidor.
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$projectRoot = realpath(__DIR__ . "/..");
$relativePath = '';

if ($documentRoot && $projectRoot && strpos($projectRoot, $documentRoot) === 0) {
    $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', substr($projectRoot, strlen($documentRoot)));
}
define("BASE_URL", "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim($relativePath, '/'));
