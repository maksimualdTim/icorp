<?php

namespace App\services;

class Db extends \SQLite3
{

    public function __construct(string $filename, int $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, string $encryptionKey = null)
    {
        if (!file_exists($filename)){
            $ar = explode('/', $filename);
            $file = array_pop($ar);
            $path = implode('/', $ar);

            if (!is_dir($path))
                mkdir($path, 0777, true);
        }
        parent::__construct($filename, $flags, $encryptionKey);
        $this->init();
    }

    public function init()
    {
        $this->query('CREATE TABLE IF NOT EXISTS "users" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "access_token" TEXT,
            "account_id" INTEGER UNIQUE,
            "refresh_token" TEXT,
            "baseDomain" VARCHAR UNIQUE,
            "access_token_expires_at" DATETIME,
            "refresh_token_expires_at" DATETIME
        )');
    }
}