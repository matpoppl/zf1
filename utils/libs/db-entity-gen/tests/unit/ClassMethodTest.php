<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\Framework\TestCase;

class ClassMethodTest extends TestCase
{
    /**
     * @covers \matpoppl\DbEntityGenerator\ClassMethod::__construct()
     * @covers \matpoppl\DbEntityGenerator\ClassMethod::__toString()
     */
    public function testString()
    {
        $prop = new ClassMethod('simple');
        self::assertEquals('public function simple();', $prop->__toString());
    }
}
