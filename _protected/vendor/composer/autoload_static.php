<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7b6f369ff6a7c1ffd21b7944ffde8dd3
{
    public static $prefixLengthsPsr4 = array (
        'k' => 
        array (
            'kartik\\plugins\\tabs\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'kartik\\plugins\\tabs\\' => 
        array (
            0 => __DIR__ . '/..' . '/kartik-v/bootstrap-tabs-x',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7b6f369ff6a7c1ffd21b7944ffde8dd3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7b6f369ff6a7c1ffd21b7944ffde8dd3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
