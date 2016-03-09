<?php

namespace jubianchi\TML\Visitor\Node;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class Fn extends Visitor
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        $left = $element->getChildren()[0];
        $rights = array_slice($element->getChildren(), 1);
        $arguments = [];

        foreach ($rights as $index => $child) {
            switch ($child->getValueToken()) {
                case 'T_NUMBER':
                    $arguments[] = $child->getValue()['value'];
                    break;

                default:
                    $arguments[] = $child->accept(
                        (new Visitor\TML())->setVariables($this->variables),
                        $handle,
                        $eldnah
                    );
            }
        }

        $name = ltrim($left->getValue()['value'], '.');

        if (function_exists($name)) {
            return call_user_func_array($name, $arguments);
        }

        if (is_callable([$this, $name]) === false) {
            throw new \LogicException('Function ' . $name .  ' does not exist');
        }

        return $this->{$name}(...$arguments);
    }

    private function affiche(...$values) {
        echo implode(' ', $values) . PHP_EOL;
    }
}
