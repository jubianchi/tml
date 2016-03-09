<?php

namespace jubianchi\TML\Visitor\Node;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class Assign extends Visitor
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        list($left, $right) = $element->getChildren();
        $name = ltrim($left->getChildren()[0]->getValueValue(), '@');

        switch ($right->getValueToken()) {
            case 'T_NUMBER':
                return $this->variables[$name] = $right->getValueValue();

            default:
                return $this->variables[$name] = $right->accept(
                    (new Visitor\TML())->setVariables($this->variables),
                    $handle,
                    $eldnah
                );
        }
    }
}
