<?php

namespace App\Core;

const StatusOk               = 200;
const StatusBadRequest       = 400;
const StatusUnauthorized     = 401;
const StatusPaymentRequired  = 402;
const StatusForbidden        = 403;
const StatusNotFound         = 404;
const StatusMethodNotAllowed = 405;
const StatusCreated          = 201;
const StatusNoContent        = 204;
const StatusFound            = 302;

class Response
{
    public static function setStatus( int $code ): void
    {
        http_response_code( $code );
    }

    public static function setStatusOk(): void
    {
        http_response_code( StatusOk );
    }

    public static function setStatusNotFound(): void
    {
        http_response_code( StatusNotFound );
    }

    public static function setStatusBadRequest(): void
    {
        http_response_code( StatusBadRequest );
    }
}