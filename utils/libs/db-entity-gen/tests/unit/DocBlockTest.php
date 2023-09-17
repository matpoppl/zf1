<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\Framework\TestCase;

class DocBlockTest extends TestCase
{
    /**
     * @covers \matpoppl\DbEntityGenerator\DocBlock::__toString()
     */
    public function testString()
    {
        $block = new DocBlock();

        $block->add('param', 'string', 'foo', 'In first');
        $block->add('var', 'int|NULL', 'bar', 'In second');
        $block->add('return', 'bool', null, 'Out');

        self::assertEquals(file_get_contents(ENTITYMANAGER_TEST_DATA . 'DocBlockTest-testString.txt'), $block->__toString());
    }
}
