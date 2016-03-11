<?php

namespace jubianchi\TML\Tests\Units;

use mageekguy\atoum;
use Hoa\Compiler;
use Hoa\File;
use Hoa\Regex;
use Hoa\Math;

class Grammar extends atoum\test
{
    public function testBuildParser()
    {
        $this
            ->given($this->newTestedInstance)
            ->then
                ->object($this->testedInstance->buildParser())->isInstanceOf(Compiler\Llk\Parser::class)
        ;
    }

    public function testTML($expression)
    {
        $this
            ->given($parser = $this->newTestedInstance->buildParser())
            ->then
                ->object($parser->parse($expression))->isInstanceOf(Compiler\Llk\TreeNode::class)
        ;
    }

    protected function testTMLDataProvider()
    {
        $parser = $this->newTestedInstance(__DIR__ . '/../tml.pp')->buildParser();
        $sampler = new Compiler\Llk\Sampler\BoundedExhaustive(
            $parser,
            new Regex\Visitor\Isotropic(new Math\Sampler\Random()),
            15
        );

        return iterator_to_array($sampler);
    }
}
