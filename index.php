<?php

require __DIR__ . '/vendor/autoload.php';

class Transpiler implements \Hoa\Visitor\Visit
{
    protected $code = '';

    public function visit(\Hoa\Visitor\Element $element, &$handle = null, $eldnah = null)
    {
        $this->code = 'function ';

        switch ($element->getId()) {
            case '#argument_list':
                $this->code .= '(';

                $args = [];

                foreach ($element->getChildren() as $child) {
                    $value = $child->getValue();

                    if ($value['token'] !== 'T_VAR') {
                        continue;
                    }

                    $args[] = $value['value'];
                }

                $this->code .= implode(', ', $args) . ')';
                break;

            default:
                var_dump($element);
        }

        return $this->code;
    }
}

$compiler = Hoa\Compiler\Llk\Llk::load(new Hoa\File\Read(__DIR__ . '/lang.pp'));
$ast = $compiler->parse(file_get_contents(__DIR__ . '/code.php.next'));

$transpiler = new Transpiler();
var_dump($transpiler->visit($ast));

