<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit57aeec99f81c6ab070354d61612f5de0
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'System\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'System\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit57aeec99f81c6ab070354d61612f5de0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit57aeec99f81c6ab070354d61612f5de0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}