<?php

namespace Commands;

use Interfaces\CommandInterface;
use Classes\Database;
use Parsedown;

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

        $parsedown = new Parsedown();

        foreach ($articles as $article) {
            $content = $parsedown->parse($article->content);
            $article->content = $content;
        }

        foreach ($articles as $article) {
            $database->store_article($article);
        } 

        echo 'Done!' . PHP_EOL;
    }
}