<?php


namespace App\Security;


use Exception;

class TokenGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    public function getRandomSecureToken(): string
        {
            $token = '';
            $maxNumber = strlen(self::ALPHABET);

            for ($i = 0; $i < 30;$i++){
                try {
                    $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
                } catch (Exception $e) {
                    return new Exception("Unable to generate confirmation Token", 500);
                }
            }

            return $token;
        }
}