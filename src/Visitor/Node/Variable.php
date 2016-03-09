<?php

namespace jubianchi\TML\Visitor\Node;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class Variable extends Visitor
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        $variable = $element->getChildren()[0];
        $name = ltrim($variable->getValueValue(), '@');

        return $this->check($name, $variable)->variables[$name];
    }

    protected function check($name, $variable)
    {
        return $this;
    }
}
