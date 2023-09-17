<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7b848f0b82b942b09c64cec4354a38c7
{
    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'matpoppl\\DbEntityGenerator\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'matpoppl\\DbEntityGenerator\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7b848f0b82b942b09c64cec4354a38c7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7b848f0b82b942b09c64cec4354a38c7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7b848f0b82b942b09c64cec4354a38c7::$classMap;

        }, null, ClassLoader::class);
    }
}