<?php

namespace Commands;

use Interfaces\CommandInterface;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Deprecation;
use Classes\Logger;

class CompileFrontend implements CommandInterface
{
    protected string $description = "This will compile SCSS assets";

    public function run(): void
    {
        $compiler = new Compiler();
        $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
        $compiler->setSilenceDeprecations([Deprecation::mixedDecls]);

        $file_location = realpath("resources/scss/style.scss");

        $result = $compiler->compileFile($file_location);

        // Create the directory if it does not exist
        if (!file_exists(realpath("public/css"))) {
            mkdir(realpath("public") . '/css', 0755);
        }

        file_put_contents(realpath("public/css") . "/app.min.css", $result->getCss());

        $logger = new Logger();
        $logger->log(date("Y-m-d H:i:s") . ': success - SCSS compiled and placed in /public/css/app.min.css');

        echo 'Done!' . PHP_EOL;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
