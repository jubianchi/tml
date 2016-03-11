<?php

namespace jubianchi\TML;

use Hoa\Compiler;
use Hoa\File;

class Grammar
{
    private $grammar;

    public function __construct($grammar = null)
    {
        $this->grammar = $grammar ?: __DIR__ . '/tml.pp';
    }

    public function buildParser()
    {
        return Compiler\Llk\Llk::load(new File\Read($this->grammar));
    }
}
