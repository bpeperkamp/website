<?php

namespace Classes;

use stdClass;
use SQLite3;
use Classes\Logger;

class Database
{
    public function store_article(stdClass $article)
    {
        $db = new SQLite3(realpath("./database") . "/database.db", SQLITE3_OPEN_READWRITE);

        $category = $this->store_category($article->category);

        $created_at = strtotime($article->created_at);
        $updated_at = strtotime($article->updated_at);

        $statement = $db->prepare('INSERT INTO "articles" ("title", "slug", "content", "created_at", "updated_at", "category_id")
            VALUES (:title, :slug, :content, :created_at, :updated_at, :category_id) 
            ON CONFLICT(title) DO UPDATE SET title=:title, slug=:slug, content=:content');

        $statement->bindValue(':title', $article->title);
        $statement->bindValue(':slug', $article->slug);
        $statement->bindValue(':content', $article->content);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s', $created_at));
        $statement->bindValue(':updated_at', date('Y-m-d H:i:s', $updated_at));
        $statement->bindValue(':category_id', $category);

        $statement->execute();

        return $db->lastInsertRowID();
    }

    public function store_category(stdClass $category)
    {
        $db = new SQLite3(realpath("./database") . "/database.db", SQLITE3_OPEN_READWRITE);

        $statement = $db->prepare('INSERT INTO "categories" ("title", "slug")
            VALUES (:title, :slug) 
            ON CONFLICT(title) DO UPDATE SET title=:title');

        $statement->bindValue(':title', $category->title);
        $statement->bindValue(':slug', $category->slug);

        $statement->execute();

        // If it already exists, return fetched id
        if ($db->lastInsertRowID() === 0) {
            $statement = $db->prepare('SELECT * FROM "categories" WHERE "title" = :title');
            $statement->bindValue(':title', $category->title);
            $result = $statement->execute();
            $result = $result->fetchArray(SQLITE3_ASSOC);

            return $result["id"];
        }

        return $db->lastInsertRowID();
    }

    public function get_articles($limit = 8): array
    {
        $db = new SQLite3(realpath("../database") . "/database.db", SQLITE3_OPEN_READONLY);

        $statement = $db->prepare('SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit');
        $statement->bindValue(':limit', $limit);
        $result = $statement->execute();

        $rows = [];

        while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $res;
        }

        return $rows;
    }

    public function get_article_by_slug(string $slug): array|bool
    {
        $db = new SQLite3(realpath("../database") . "/database.db", SQLITE3_OPEN_READONLY);

        $statement = $db->prepare('SELECT * FROM "articles" WHERE "slug" = :slug LIMIT 1');
        $statement->bindValue(':slug', $slug);
        $result = $statement->execute();

        $result = $result->fetchArray(SQLITE3_ASSOC);

        return $result;
    }

    public function get_categories(): array
    {
        $db = new SQLite3(realpath("../database") . "/database.db", SQLITE3_OPEN_READONLY);

        $statement = $db->prepare('SELECT * FROM categories');

        $result = $statement->execute();

        $rows = [];
        while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $res;
        }

        return $rows;
    }

    public function get_articles_by_category(string $slug)
    {
        $db = new SQLite3(realpath("../database") . "/database.db", SQLITE3_OPEN_READONLY);

        // Get the articles from the category
        $statement = $db->prepare('SELECT * FROM "articles" WHERE "category_id" = (SELECT id FROM "categories" WHERE "slug" = :slug LIMIT 1)');
        $statement->bindValue(':slug', $slug);
        $result = $statement->execute();

        $rows = [];
        while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $res;
        }

        return $rows;
    }

    public function search_articles(string $query): array
    {
        $db = new SQLite3(realpath("../database") . "/database.db", SQLITE3_OPEN_READONLY);

        $statement = $db->prepare('SELECT title, slug, content FROM "articles" WHERE "title" LIKE :query OR "content" LIKE :query');
        $statement->bindValue(':query', "%" . $query . "%");
        $result = $statement->execute();

        $rows = [];
        while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $res;
        }

        return $rows;
    }
}
