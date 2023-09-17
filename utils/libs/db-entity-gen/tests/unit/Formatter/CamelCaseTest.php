<?php

namespace matpoppl\DbEntityGenerator\Formatter;

use PHPUnit\Framework\TestCase;

class CamelCaseTest extends TestCase
{
    /**
     * @covers \matpoppl\DbEntityGenerator\Formatter\CamelCase::to()
     */
    public function testTo()
    {
        $fmt = new CamelCase();
        self::assertEquals('fooBarBaz', $fmt->to('foo_bar_baz'));
        self::assertEquals('fooBarBaz', $fmt->to('foo-bar-baz'));
        self::assertEquals('fooBarBaz', $fmt->to('foo.bar.baz'));
    }

    /**
     * @covers \matpoppl\DbEntityGenerator\Formatter\CamelCase::from()
     */
    public function testFrom()
    {
        $fmt = new CamelCase();
        self::assertEquals('foo_bar_baz', $fmt->from('fooBarBaz'));
    }
}
