<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit365338cb4b7dc88b2ccda0d9e2eddbb6
{
    public static $files = array (
        '20872bbaff0e3115cc7db5ab4a7d607e' => __DIR__ . '/..' . '/wpfactory/wpfactory-promoting-notice/src/php/functions.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WPFactory\\Promoting_Notice\\Core' => __DIR__ . '/..' . '/wpfactory/wpfactory-promoting-notice/src/php/class-core.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit365338cb4b7dc88b2ccda0d9e2eddbb6::$classMap;

        }, null, ClassLoader::class);
    }
}