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

                case 'T_CONST':
                    $name = $child->getValueValue();

                    if (defined($name) === false) {
                        throw new \LogicException('Undefined constant ' . $name);
                    }

                    $expr .= constant($name);
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

                    if ($child->getId() === '#rvar' && is_numeric($result) === false) {
                        throw new \LogicException('Unexpected operand ' . $child->getChild(0)->getValueValue() . ' with type ' . gettype($result) . ': ' . var_export($result, true));
                    }

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
