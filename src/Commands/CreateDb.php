<?php

namespace Commands;

use Interfaces\CommandInterface;
use SQLite3;

class CreateDb implements CommandInterface
{
    protected string $description = "This will create an empty DB";

    public function run(): void
    {
        $db = new SQLite3(realpath("./database") . "/database.db", SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

        $db->query('CREATE TABLE IF NOT EXISTS "articles" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "title" VARCHAR(255) UNIQUE NOT NULL,
            "slug" VARCHAR(255) NOT NULL,
            "content" TEXT NOT NULL,
            "category_id" INTEGER,
            "created_at" DATETIME,
            "updated_at" DATETIME,
            FOREIGN KEY (category_id) REFERENCES categories (id)
        )');

        $db->query('CREATE TABLE IF NOT EXISTS "categories" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "title" VARCHAR(255) UNIQUE NOT NULL,
            "slug" VARCHAR(255) NOT NULL
        )');

        $db->query("PRAGMA journal_mode=WAL;");
        $db->query("PRAGMA synchronous=NORMAL");
        $db->query("PRAGMA cache_size=-64000");
        $db->query("PRAGMA mmap_size=268435456");
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
