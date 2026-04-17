<?php

return new PDO(
    "pgsql:host=172.25.76.229;port=5432;dbname=ficiencias",
    "postgres",
    "12345",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
