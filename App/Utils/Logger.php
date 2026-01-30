<?php

namespace App\Utils;

class Logger
{
    private const LOG_FILE = __DIR__ . '/../../error.log';

    public static function error(string $message, array $context = []): void
    {
        $payload = '[' . date('Y-m-d H:i:s') . '] ' . $message;

        if (!empty($context)) {
            $payload .= ' | ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        }

        $payload .= PHP_EOL;

        // type 3 = écrit dans un fichier (append)
        error_log($payload, 3, self::LOG_FILE);
    }
}