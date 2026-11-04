<?php
/**
 * Handler seguro de upload de vídeos.
 * Aceita: mp4, webm, ogg
 * Tamanho máximo: 50 MB
 * Requer sessão de administrador ativa.
 */

// Captura qualquer output indesejado (warnings/notices do PHP com display_errors=1)
ob_start();

require_once __DIR__ . '/config.php';

// Descarta warnings que possam ter saído durante o require
ob_clean();

// A partir daqui toda a saída é JSON
header('Content-Type: application/json; charset=utf-8');

// ── Segurança: somente admins (verifica sessão diretamente) ───────────────
$user = $_SESSION['usuario_logado'] ?? null;
if (!$user || ($user['perfil'] ?? '') !== 'administrador') {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado.']);
    exit;
}

// ── Somente POST ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
    exit;
}

// ── Verificar arquivo recebido ─────────────────────────────────────────────
reset($_FILES);
$file = current($_FILES);

if (!$file || !is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Nenhum arquivo recebido.']);
    exit;
}

// ── Limite de tamanho: 50 MB ───────────────────────────────────────────────
$maxBytes = 50 * 1024 * 1024;
if ($file['size'] > $maxBytes) {
    http_response_code(413);
    echo json_encode(['error' => 'Arquivo muito grande. Máximo: 50 MB.']);
    exit;
}

// ── Validar MIME type real ─────────────────────────────────────────────────
$allowedMimes = ['video/mp4', 'video/webm', 'video/ogg'];
$finfo        = new finfo(FILEINFO_MIME_TYPE);
$realMime     = $finfo->file($file['tmp_name']);

if (!in_array($realMime, $allowedMimes, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de arquivo não permitido. Use MP4, WebM ou OGG.']);
    exit;
}

// ── Extensões permitidas ───────────────────────────────────────────────────
$allowedExts = ['mp4', 'webm', 'ogg'];
$ext         = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExts, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Extensão não permitida.']);
    exit;
}

// ── Gerar nome único e seguro ──────────────────────────────────────────────
$destDir  = __DIR__ . '/../public/uploads/videos/';
$fileName = uniqid('vid_', true) . '.' . $ext;
$destPath = $destDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao salvar o vídeo.']);
    exit;
}

// ── Retornar URL pública ───────────────────────────────────────────────────
$publicUrl = BASE_URL . '/public/uploads/videos/' . $fileName;

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['location' => $publicUrl]);

