<?php

namespace Shift\Packages;

class Registry
{
    /**
     * Returns the type and constraint of packages included
     * in a Laravel project for the specified version.
     *
     * @param string $version 'latest', '8.x', '7.x', '6.x'
     * @return array
     */
    public static function corePackagesFor(string $version): array
    {
        self::verifyVersion($version);

        $packages = self::corePackages($version);

        if ($version === 'latest') {
            return $packages;
        }

        return self::pluckLatestConstraint($packages);
    }

    /**
     * Returns the type and constraint of popular packages
     * in the Laravel community for the specified version.
     *
     * @param string $version 'latest', '8.x', '7.x', '6.x'
     * @return array
     */
    public static function communityPackagesFor(string $version): array
    {
        self::verifyVersion($version);

        $packages = self::communityPackages($version);

        if ($version === 'latest') {
            return $packages;
        }

        return self::pluckLatestConstraint($packages);
    }

    /**
     * Returns a list of all Illuminate packages.
     *
     * @return array
     */
    public static function illuminatePackages(): array
    {
        return [
            'illuminate/auth',
            'illuminate/broadcasting',
            'illuminate/bus',
            'illuminate/cache',
            'illuminate/config',
            'illuminate/console',
            'illuminate/container',
            'illuminate/contracts',
            'illuminate/cookie',
            'illuminate/database',
            'illuminate/encryption',
            'illuminate/events',
            'illuminate/filesystem',
            'illuminate/hashing',
            'illuminate/http',
            'illuminate/log',
            'illuminate/mail',
            'illuminate/notifications',
            'illuminate/pagination',
            'illuminate/pipeline',
            'illuminate/queue',
            'illuminate/redis',
            'illuminate/routing',
            'illuminate/session',
            'illuminate/support',
            'illuminate/testing',
            'illuminate/translation',
            'illuminate/validation',
            'illuminate/view',
        ];
    }

    private static function corePackages(string $version)
    {
        static $packages = null;

        if (is_null($packages)) {
            $packages = require __DIR__ . '/../data/core.php';
        }

        return $packages[$version];
    }

    private static function communityPackages(string $version)
    {
        static $packages = null;

        if (is_null($packages)) {
            $packages = array_merge_recursive(
                require __DIR__ . '/../data/community.php',
                require __DIR__ . '/../data/commercial.php'
            );
        }

        return $packages[$version];
    }

    private static function pluckLatestConstraint($packages): array
    {
        $latest = [];

        foreach ($packages as $name => $attributes) {
            $latest[$name] = [
                'type' => $attributes['type'],
                'constraint' => $attributes['constraints']['latest'],
            ];
        }

        return $latest;
    }

    private static function verifyVersion(string $version)
    {
        if (!in_array($version, ['latest', '8.x', '7.x', '6.x'])) {
            throw new \InvalidArgumentException(sprintf('Unexpected version (%s), version must be: latest, 8.x, 7.x, or 6.x', $version));
        }
    }
}
