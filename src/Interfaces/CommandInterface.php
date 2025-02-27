<?php

namespace Interfaces;

interface CommandInterface
{
    public function run(): void;

    public function getDescription(): string;
}
