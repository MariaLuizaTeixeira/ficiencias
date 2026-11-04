<?php


/***************************************************
 * Somente estas origens podem enviar imagens *
 ***************************************************/
$accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com");

/*********************************************
 * Altere esta linha para definir a pasta de upload *
 *********************************************/
$imageFolder = UPLOAD_PATH;

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // requisições da mesma origem não enviam o cabeçalho Origin. Se ele existir, precisa ser válido.
    if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    } else {
        header("HTTP/1.1 403 Origin Denied");
        return;
    }
}

// Não processa o upload em requisições do tipo OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    return;
}

reset($_FILES);
$temp = current($_FILES);
if (is_uploaded_file($temp['tmp_name'])) {
    /*
      If your script needs to receive cookies, set images_upload_credentials : true in
      the configuration and enable the following two headers.
    */
    // header('Access-Control-Allow-Credentials: true');
    // header('P3P: CP="There is no P3P policy."');

    // Valida o nome do arquivo
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verifica a extensão
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    // Aceita o upload se não houver origem ou se ela for permitida
    $filetowrite = $imageFolder . $temp['name'];
    move_uploaded_file($temp['tmp_name'], $filetowrite);

    // Descobre a URL base
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
    $baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";

    // Responde ao upload bem-sucedido com JSON.
    // A chave location informa o caminho da imagem salva.
    // { location : '/caminho/da/imagem/enviada'}
    echo json_encode(array('location' => $baseurl . $filetowrite));
} else {
    // Avisa o editor que o upload falhou
    header("HTTP/1.1 500 Server Error");
}
