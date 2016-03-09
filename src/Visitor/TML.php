<?php

namespace jubianchi\TML\Visitor;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class TML extends Visitor
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        $start = microtime(true);

        switch ($element->getId()) {
            case '#tml':
                foreach ($element->getChildren() as $child) {
                    $child->accept($this, $handle, $eldnah) . PHP_EOL;
                }
                return microtime(true) - $start;

            case '#assign':
                $visitor = new Node\Assign();
                break;

            case '#expr':
                $visitor = new Node\Expr();
                break;

            case '#fn':
                $visitor = new Node\Fn();
                break;

            case '#str':
                $visitor = new Node\Str();
                break;

            case '#rvar':
                $visitor = new Node\Variable\Right();
                break;

            case '#lvar':
                $visitor = new Node\Variable();
                break;

            default:
                return microtime(true) - $start;
        }

        return $element->accept(
            $visitor->setVariables($this->variables),
            $handle,
            $eldnah
        );
    }
}
