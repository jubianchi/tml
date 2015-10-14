<?php

require __DIR__ . '/vendor/autoload.php';

class Transpiler implements \Hoa\Visitor\Visit
{
    protected static $level = 0;

    public function visit(\Hoa\Visitor\Element $element, &$handle = null, $eldnah = null)
    {
        $code = '';

        switch ($element->getId()) {
            case '#php':
                $code = '<?php' . PHP_EOL;

                foreach ($element->getChildren() as $child) {
                    $code .= $child->accept($this, $handle, $eldnah);
                }
                break;

            case '#function':
                $code = 'function ';

                foreach ($element->getChildren() as $child) {
                    $code .= $child->accept($this, $handle, $eldnah);
                }

                $code .= '};' . PHP_EOL;

                static::$level--;
                break;

            case '#arguments':
                $code .= '(';

                $args = [];

                foreach ($element->getChildren() as $child) {
                    $value = $child->getValue();

                    $args[] = $value['value'];
                }

                $code .= implode(', ', $args) . ') {' . PHP_EOL;

                static::$level++;
                break;

            case '#body':
                foreach ($element->getChildren() as $child) {
                    if ($child->getValue()['token'] == 'T_RIGHT_CURLY') {
                        static::$level--;
                    }

                    $code .= str_repeat("\t", static::$level) . $child->getValue()['value'] . PHP_EOL;

                    if ($child->getValue()['token'] == 'T_LEFT_CURLY') {
                        static::$level++;
                    }
                }
                break;
        }

        return $code;
    }
}

$compiler = Hoa\Compiler\Llk\Llk::load(new Hoa\File\Read(__DIR__ . '/lang.pp'));
$ast = $compiler->parse(file_get_contents(__DIR__ . '/code.php.next'));

$transpiler = new Transpiler();

echo $transpiler->visit($ast);
