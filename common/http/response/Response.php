<?php

namespace response;
class Response
{
    public static function ok($data): string|false
    {
        http_response_code(200);
        return json_encode($data);
    }

    public static function notFound(): string|false {
        return http_response_code(404);
    }

    public static function created($data): string|false {
        http_response_code(201);
        return json_encode($data);
    }
}