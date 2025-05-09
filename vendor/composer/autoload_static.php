<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit22e0dbd992e47dbd658e1d8616df8a3e
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'ElastiForm\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ElastiForm\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit22e0dbd992e47dbd658e1d8616df8a3e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit22e0dbd992e47dbd658e1d8616df8a3e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit22e0dbd992e47dbd658e1d8616df8a3e::$classMap;

        }, null, ClassLoader::class);
    }
}
