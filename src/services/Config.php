<?php

namespace App\services;

class Config
{
    public static function getClient(string $client, $onlyTokens = false): array
    {
        $clients = include_once 'configs/clients.php';
        if ($onlyTokens)
            unset($clients['widgets']);
        return $clients[$client];
    }
}