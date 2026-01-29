<?php

namespace App\Utils;

class Logger
{
    public static function error(string $message, array $context = []): void
    {
        $payload = $message;
        if (!empty($context)) {
            $payload .= " | " . json_encode($context, JSON_UNESCAPED_SLASHES);
        }
        error_log($payload);
    }
}
