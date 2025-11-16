<?php

namespace App\Core;

class GenerateKey
{
    public static function generateKey(): string
    {
        // Gera 24 bytes aleatórios
        $randomBytes = random_bytes(24);

        // Codifica em Base64, o que resultará em uma string de 32 caracteres
        $key = base64_encode($randomBytes);

        return $key;
    }

    public static function generateKeyWithRange(string $prefix = '', string $sufix = '', string $range = ''): string
    {
        if (empty($range)) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $characters = $range;
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        $length = 32 - (strlen($prefix) + strlen($sufix));
        if ($length < 0) {
            $length = 0;
        }

        for ($i = 0; $i < $length; $i++) {
            if ($i === 0) {
                $randomString .= $prefix;
            }
            if ($i === $length - (1 + strlen($sufix))) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
                $randomString .= $sufix;
                break;
            }
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
