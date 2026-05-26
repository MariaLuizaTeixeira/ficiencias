<?php

//Mostrar erros do PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Configurar essas variáveis de acordo com o seu ambiente
define("DB_SGBD", "pgsql");
define("DB_HOST", "172.25.76.229");
define("DB_PORT", "5432");
define("DB_NAME", "ficiencias");
define("DB_USER", "postgres");
define("DB_PASSWORD", "12345");