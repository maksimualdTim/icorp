<?php

namespace App\services;

class Db extends \SQLite3
{

    public function __construct(string $filename, int $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, string $encryptionKey = null)
    {
        parent::__construct($filename, $flags, $encryptionKey);
        $this->init();
    }

    public function init()
    {
        $this->query('CREATE TABLE IF NOT EXISTS "users" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "access_token" TEXT,
            "client_id" INTEGER,
            "refresh_token" TEXT,
            "baseDomain" VARCHAR,
            "access_token_expires_at" DATETIME,
            "refresh_token_expires_at" DATETIME
        )');
    }
}