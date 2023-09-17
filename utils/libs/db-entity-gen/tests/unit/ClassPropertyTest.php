<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\Framework\TestCase;

class ClassPropertyTest extends TestCase
{
    /**
     * @covers \matpoppl\DbEntityGenerator\ClassProperty::__construct()
     * @covers \matpoppl\DbEntityGenerator\ClassProperty::__toString()
     * @covers \matpoppl\DbEntityGenerator\ClassProperty::setValue()
     * @covers \matpoppl\DbEntityGenerator\ClassProperty::setDatatype()
     * @covers \matpoppl\DbEntityGenerator\ClassProperty::setVisibility()
     */
    public function testString()
    {
        $prop = new ClassProperty('simple');
        self::assertEquals('public $simple;', $prop->__toString());

        $prop = new ClassProperty('foo');

        $prop->setValue(41.0);
        $prop->setDatatype('float');
        $prop->setVisibility('private');

        self::assertEquals('private float $foo = 41.0;', $prop->__toString());

        $prop = new ClassProperty('bar');

        $prop->setValue(false);
        $prop->setDatatype('bool');
        $prop->setVisibility('protected');

        self::assertEquals('protected bool $bar = false;', $prop->__toString());

        $prop = new ClassProperty('baz');
        $prop->setValue('foo "bar" baz');
        self::assertEquals('public $baz = \'foo "bar" baz\';', $prop->__toString());

        $prop = new ClassProperty('TYPE_FIRST');
        $prop->setConst('const');
        $prop->setValue(1);
        self::assertEquals('public const TYPE_FIRST = 1;', $prop->__toString());
    }
}
