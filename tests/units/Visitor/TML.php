<?php

namespace jubianchi\TML\Tests\Units\Visitor;

use mageekguy\atoum;
use Hoa\Compiler;
use Hoa\File;
use Hoa\Regex;
use Hoa\Math;
use jubianchi\TML\Grammar;

class TML extends atoum\test
{
    public function testVisit($expression)
    {
        $this
            ->executeOnFailure(function() use ($expression) { var_dump($expression); })
            ->given(
                $visitor = $this->newTestedInstance,
                $parser = (new Grammar())->buildParser()
            )
            ->if($ast = $parser->parse($expression))
            ->then
                ->variable($visitor->visit($ast))->isNull
        ;
    }

    protected function testVisitDataProvider()
    {
        $parser = (new Grammar(__DIR__ . '/../../tml.pp'))->buildParser();
        $sampler = new Compiler\Llk\Sampler\BoundedExhaustive(
            $parser,
            new Regex\Visitor\Isotropic(new Math\Sampler\Random()),
            15
        );

        return iterator_to_array($sampler);
    }
}
