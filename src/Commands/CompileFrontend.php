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

        file_put_contents("./public/css/bootstrap.min.css", $result->getCss());

        $logger = new Logger();
        $logger->log(date("Y-m-d H:i:s") . ': success - SCSS compiled and placed in /public/css/bootstrap.min.css');

        echo 'Done!' . PHP_EOL;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
