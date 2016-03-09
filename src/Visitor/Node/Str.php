<?php

namespace jubianchi\TML\Visitor\Node;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class Str extends Visitor
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        $string = '';

        foreach ($element->getChildren() as $child) {
            $string .= $child->getValueValue();
        }

        return $string;
    }
}
