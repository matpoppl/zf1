<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\Framework\TestCase;

class ClassGeneratorTest extends TestCase
{
    public function testString()
    {
        $gen = new ClassGenerator('Simple');

        $gen->setProperties(['foo' => true, 'bar' => 1.2, 'baz' => 'value']);

        self::assertEquals(file_get_contents(ENTITYMANAGER_TEST_DATA . 'ClassGeneratorTest-testString.txt'), $gen->__toString());
    }
}
