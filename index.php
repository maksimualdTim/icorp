<?php
use App\services\AmoApiService;

header('Access-Control-Allow-Origin: *');
defined('HOST') or define('HOST', 'https://bc27-82-215-98-110.eu.ngrok.io');
include_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['from_widget']) && $_GET['from_widget'] && isset($_GET['client_id']) && $_GET['client_id'])
    $service = new AmoApiService([
        'clientId' => $_GET['client_id'],
        'clientSecret' => 'ur6HabJ4iRMAU10Qf4kbKrG44i7J5TYlfPMGpuoIfYFHlz8jDNPunvXLym8cHQ2M',
        'redirectUri' => HOST,
    ]);
else
    $service = new AmoApiService([
        'clientId' => '545c3888-6a42-4e99-9c6f-dbdf7b7d63ed',
        'clientSecret' => 'gQ38ggVfwZfuo9cR8G2XstAdXLUWf1SQeq6ABj8dnOYx5yhnuacORX3vsMRQ7job',
        'redirectUri' => HOST,
    ]);
