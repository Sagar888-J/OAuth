<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit297c43fa90683646372a2dbcd0e95869
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit297c43fa90683646372a2dbcd0e95869', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit297c43fa90683646372a2dbcd0e95869', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit297c43fa90683646372a2dbcd0e95869::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
