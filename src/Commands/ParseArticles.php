<?php

namespace Commands;

use Interfaces\CommandInterface;
use Classes\Database;

class ParseArticles implements CommandInterface 
{
    protected string $description = "This command will read the articles json file";

    public function getDescription(): string 
    {
        return $this->description;
    }

    public function run(): void
    {
        $file = realpath("storage/private/articles.json");
        $file = file_get_contents($file);
        $articles = json_decode($file);

        $database = new Database();

        foreach ($articles as $article) {
            $database->store_article($article);
        } 

        echo 'Done!' . PHP_EOL;
    }
}