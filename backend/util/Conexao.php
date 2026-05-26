<?php

namespace Util;

use PDO;

class Conexao {

    private static ?PDO $conexao = null;

    public static function getConexao(): PDO {
        if (self::$conexao == null) {

            $opcoes = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            self::$conexao = new PDO(DB_SGBD . ":host=" . DB_HOST .  ";port=" . DB_PORT . ";dbname=" . DB_NAME,
                DB_USER, DB_PASSWORD, $opcoes);
        }

        return self::$conexao;
    }
}