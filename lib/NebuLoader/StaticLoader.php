<?php

namespace NebuLoader;

class StaticLoader
{
    private static array $loaded = [];
    private static array $prefixes;
    private static array $dependencies;
    private static $bassPath;

    public function __construct($bassPath)
    {
        self::$bassPath = $bassPath;
    }

    public static function isLoaded(string $name): bool
    {
        return isset(self::$loaded[$name]);
    }

    public static function load(string $dir): void
    {
        if (file_exists($dir) and !self::isLoaded($dir)) {
            self::$loaded[$dir] = require $dir;
        }
    }

    public function setPrefixes(Config $configs): void
    {
        self::$prefixes = $configs->getPrefixes();
    }

    public function setDependencies(Config $configs): void
    {
        self::$dependencies = $configs->getDependencies();
    }

    public static function prefix(string $fullName): string
    {
        return explode('\\', $fullName)[0];
    }

    public static function isAvailable(string $name): bool
    {
        return array_key_exists(self::prefix($name) . '\\', self::$prefixes);
    }

    public static function isDependent(string $fullName): bool
    {
        return !(array_search(self::prefix($fullName), self::$dependencies) === false);
    }

    public static function getFullPath(string $path): string
    {
        return self::$bassPath . '/' . $path;
    }

    private static function changePrefix(string $fullName, string $newPrefix): string
    {
        $fullName = explode('\\', $fullName);
        $fullName[0] = $newPrefix;
        return implode('/', $fullName) . '.php';
    }

    private static function getFullPathForNamespace(string $namespace): string
    {
        return self::getFullPath(self::changePrefix($namespace, trim(self::$prefixes[self::prefix($namespace) . '\\'], '/')));
    }

    private static function getLibraryPathForNamespace(string $namespace): string
    {
        return self::getFullPath(self::changePrefix($namespace, 'lib/' . self::prefix($namespace)));
    }

    public static function classLoader(): callable
    {
        return function ($nameSpace) {
            if (self::isAvailable($nameSpace)) {

                $filePath = self::getFullPathForNamespace($nameSpace);
                self::load($filePath);

            } else if (self::isDependent($nameSpace)) {

                $filePath = self::getLibraryPathForNamespace($nameSpace);
                self::load($filePath);

            }
        };
    }
}