#!/usr/bin/env php
<?php

/**
 * Builds data/laravel-framework.json from the composer.json "require"
 * of each laravel/framework series branch. Skips php, extensions, composer platform packages,
 * illuminate/*, and symfony/* as they are tracked elsewhere.
 */

$series = ['13.x', '12.x', '11.x', '10.x', '9.x', '8.x', '7.x', '6.x'];

$data = [];

foreach ($series as $version) {
    $json = file_get_contents('https://raw.githubusercontent.com/laravel/framework/'.$version.'/composer.json');

    if ($json === false) {
        fwrite(STDERR, "Unable to download composer.json for {$version}".PHP_EOL);
        exit(1);
    }

    $require = json_decode($json, true)['require'] ?? [];

    $packages = array_filter(
        $require,
        fn ($constraint, $name) => $name !== 'php'
            && ! str_starts_with($name, 'ext-')
            && ! str_starts_with($name, 'composer-')
            && ! str_starts_with($name, 'illuminate/')
            && ! str_starts_with($name, 'symfony/'),
        ARRAY_FILTER_USE_BOTH
    );

    ksort($packages);

    $data[$version] = $packages;
}

file_put_contents(
    __DIR__.'/../data/laravel-framework.json',
    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
);
