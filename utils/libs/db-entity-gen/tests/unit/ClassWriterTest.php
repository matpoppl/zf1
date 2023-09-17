<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\Framework\TestCase;

class ClassWriterTest extends TestCase
{
    public function testMatch()
    {
        $writer = new ClassWriter();

        $writer->addPRS0('Foo_Bar_Baz_', '/root/libs/foo/src');
        $writer->addPRS4('Quz\\Bar\\Baz\\', '/root/libs/quz/src');

        self::assertEquals('/root/libs/foo/src/Foo/Bar/Baz/Quz/Buz/ClassFile.php', $writer->match('Foo_Bar_Baz_Quz_Buz_ClassFile'));
        self::assertEquals('/root/libs/quz/src/Buz/ClassFile.php', $writer->match('Quz\\Bar\\Baz\\Buz\\ClassFile'));
    }
}
