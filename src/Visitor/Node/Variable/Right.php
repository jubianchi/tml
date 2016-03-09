<?php

namespace jubianchi\TML\Visitor\Node\Variable;

use jubianchi\TML\Visitor\Node;

class Right extends Node\Variable
{
    protected function check($name, $variable)
    {
        if (array_key_exists($name, $this->variables) === false) {
            throw new \LogicException('Undefined variable ' . $variable->getValueValue());
        }

        return $this;
    }
}
