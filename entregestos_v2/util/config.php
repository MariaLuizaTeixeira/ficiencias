<?php

//Mostrar erros do PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Configurar essas variáveis de acordo com o seu ambiente
define("DB_HOST", "localhost");
define("DB_NAME", "entregestos_db");
define("DB_USER", "root");
define("DB_PASSWORD", "");   

define("AMB_DEV", true);

define("BASE_URL", "/entregestos_v2");
