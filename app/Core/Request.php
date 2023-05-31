<?php

namespace App\Core;

class Request
{
    private static array $query = [];

    private static array $body = [];

    public static function path(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return trim($path, '/');
        }

        return trim(substr($path, 0, $position), '/');
    }

    public static function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function query(): array
    {
        if (!!self::$query) {
            return self::$query;
        }

        foreach ($_GET as $k => $v) {
            self::$query[$k] = filter_input(INPUT_GET, $k, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return self::$query;
    }

    public static function body(): array
    {
        if (!!self::$body) {
            return self::$body;
        }

        foreach ($_POST as $k => $v) {
            self::$body[$k] = filter_input(INPUT_POST, $k, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return self::$body;
    }

    public static function rawBody(): string
    {
        return '';
    }

    public static function get(string $key, $default = null): string|null
    {
        return filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS) ?? $default;
    }

    public static function post(string $key, $default = null): string|null
    {
        return filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS) ?? $default;
    }

    public static function cookie(string $key, $default = null): string|null
    {
        return filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_SPECIAL_CHARS) ?? $default;
    }

    public static function has(string $type, $key): bool
    {
        switch ($type) {
            case 'GET':
                return is_array($key) ? self::hasKeysInArray($key, $_GET) : isset($_GET[$key]);
            case 'POST':
                return is_array($key) ? self::hasKeysInArray($key, $_POST) : isset($_POST[$key]);
            default:
                return false;
        }
    }

    private static function hasKeysInArray(array $keys, array $method): bool
    {
        foreach ($keys as $key) {
            if (!isset($method[$key])) {
                return false;
            }
        }
        return true;
    }
}