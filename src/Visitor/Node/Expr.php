<?php

namespace jubianchi\TML\Visitor\Node;

use Hoa\Visitor\Element;
use jubianchi\TML\Visitor;

class Expr extends Visitor
{
    const OPERATORS = [
        'T_OP_PLUS' => '+',
        'T_OP_MINUS' => '-',
        'T_OP_MULTI' => '*',
        'T_OP_DIVIDE' => '/'
    ];

    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        $expr = '';

        foreach ($element->getChildren() as $child) {
            switch ($child->getValueToken()) {
                case 'T_NUMBER':
                    $expr .= $child->getValueValue();
                    break;

                case 'T_OP_PLUS':
                case 'T_OP_MINUS':
                case 'T_OP_MULTI':
                case 'T_OP_DIVIDE':
                    $expr .= self::OPERATORS[$child->getValueToken()];
                    break;

                default:
                    $result = $child->accept(
                        (new Visitor\TML())->setVariables($this->variables),
                        $handle,
                        $eldnah
                    );

                    $expr .= ($result < 0 ? '(' . $result . ')' : $result);
            }
        }

        set_error_handler(function() use ($expr) {
            throw new \LogicException(error_get_last()['message']);
        });

        $result = eval('return ' . $expr . ';');

        restore_error_handler();

        return $result;
    }
}
