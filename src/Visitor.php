<?php

namespace jubianchi\TML;

use Hoa\Visitor;

abstract class Visitor implements Visitor\Visit
{
    protected $variables;

    public function __construct(\ArrayObject $variables = null)
    {
        $this->setVariables($variables ?: new \ArrayObject());
    }

    protected function setVariables(\ArrayObject $variables)
    {
        $this->variables = $variables;

        return $this;
    }
}
