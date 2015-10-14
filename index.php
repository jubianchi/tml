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
                break;

            case '#arguments':
                $code .= '(';

                $args = [];

                foreach ($element->getChildren() as $child) {
                    $value = $child->getValue();

                    $args[] = $value['value'];
                }

                $code .= implode(', ', $args) . ') ';
                break;

            case '#body':
                $code .= '{' . PHP_EOL;

                static::$level++;

                foreach ($element->getChildren() as $child) {
                    $code .= $child->accept($this, $handle, $eldnah);
                }

                static::$level--;

                $code .= '};' . PHP_EOL;
                break;

            case '#oneline':
                $code .= "\t" . 'return ';

                foreach ($element->getChildren() as $child) {
                    $code .= $child->accept($this, $handle, $eldnah);
                }

                $code .= ';' . PHP_EOL;
                break;

            default:
                $value = $element->getValue();

                switch ($value['token']) {
                    case 'T_RIGHT_CURLY':
                        static::$level--;

                        $code .= str_repeat("\t", static::$level) . $value['value'] . PHP_EOL;
                        break;

                    case 'T_LEFT_CURLY':
                        $code .= $value['value'] . PHP_EOL;

                        static::$level++;
                        break;

                    case 'T_SEMI_COLON':
                        $code .= $value['value'] . PHP_EOL;
                        break;

                    default:
                        $code .= str_repeat("\t", static::$level) . $value['value'];
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

