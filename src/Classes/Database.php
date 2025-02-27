<?php

namespace Classes;

use stdClass;
use SQLite3;

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
            VALUES (:title, :slug) ON CONFLICT(title) DO UPDATE SET title=:title');

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
}
