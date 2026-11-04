<?php

require_once(__DIR__ . "/config.php");

// Conexão com o banco (uma única instância compartilhada)
class Connection {

    private static ?PDO $conn = null;

    public static function getConnection() {

        if(self::$conn == null) {
            try {
               $opcoes = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);               
                $strConn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                
                self::$conn = new PDO($strConn, DB_USER, DB_PASSWORD, $opcoes);
            } catch(PDOException $e) {
                echo "Erro ao conectar na base de dados.<br>";
                print_r($e);
            }
        }
        return self::$conn;
    } 
}
