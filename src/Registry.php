<?php

namespace Shift\Packages;

class Registry
{
    /**
     * Returns the type and constraint of packages included
     * in a Laravel project for the specified version.
     */
    public static function corePackagesFor(string $laravel): array
    {
        self::verifyLaravelVersion($laravel);

        $packages = self::corePackages($laravel);

        if ($laravel === 'latest') {
            return $packages;
        }

        return self::pluckConstraints($packages);
    }

    /**
     * Returns the type and constraint of popular packages
     * in the Laravel community for the specified version.
     */
    public static function communityPackagesFor(string $laravel, string $php = null): array
    {
        self::verifyLaravelVersion($laravel);
        self::verifyPhpVersion($php);

        $packages = self::communityPackages($laravel);

        if ($laravel === 'latest') {
            return $packages;
        }

        return self::pluckConstraints($packages, $php ?? 'latest');
    }

    /**
     * Returns the latest tagged Laravel release for the series.
     */
    public static function tagForSeries($series = null): string
    {
        $tags = self::tags();

        // Laravel 9 is LTS & Latest
        return $tags['lts'];
    }

    /**
     * Returns the constraint used by the specified Laravel
     * version for the included Symfony components.
     */
    public static function symfonyConstraintFor(string $version): string
    {
        static $constraints = [
            'latest' => '^6.0',
            '9.x' => '^6.0',
            '8.x' => '^5.4',
            '7.x' => '^5.0',
            '6.x' => '^4.3.4',
        ];

        self::verifyLaravelVersion($version);

        return $constraints[$version];
    }

    /**
     * Returns a list of all Illuminate packages.
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

    /**
     * Returns a list of all core Symfony packages.
     */
    public static function symfonyPackages(): array
    {
        return [
            'symfony/all-my-sms-notifier',
            'symfony/amazon-mailer',
            'symfony/amazon-sns-notifier',
            'symfony/amazon-sqs-messenger',
            'symfony/amqp-messenger',
            'symfony/asset',
            'symfony/beanstalkd-messenger',
            'symfony/browser-kit',
            'symfony/cache',
            'symfony/clickatell-notifier',
            'symfony/config',
            'symfony/console',
            'symfony/crowdin-translation-provider',
            'symfony/css-selector',
            'symfony/debug-bundle',
            'symfony/dependency-injection',
            'symfony/discord-notifier',
            'symfony/doctrine-bridge',
            'symfony/doctrine-messenger',
            'symfony/dom-crawler',
            'symfony/dotenv',
            'symfony/error-handler',
            'symfony/esendex-notifier',
            'symfony/event-dispatcher',
            'symfony/expo-notifier',
            'symfony/expression-language',
            'symfony/fake-chat-notifier',
            'symfony/fake-sms-notifier',
            'symfony/filesystem',
            'symfony/finder',
            'symfony/firebase-notifier',
            'symfony/form',
            'symfony/framework-bundle',
            'symfony/free-mobile-notifier',
            'symfony/gateway-api-notifier',
            'symfony/gitter-notifier',
            'symfony/google-chat-notifier',
            'symfony/google-mailer',
            'symfony/http-client',
            'symfony/http-foundation',
            'symfony/http-kernel',
            'symfony/infobip-notifier',
            'symfony/intl',
            'symfony/iqsms-notifier',
            'symfony/ldap',
            'symfony/light-sms-notifier',
            'symfony/linked-in-notifier',
            'symfony/lock',
            'symfony/loco-translation-provider',
            'symfony/lokalise-translation-provider',
            'symfony/mailchimp-mailer',
            'symfony/mailer',
            'symfony/mailgun-mailer',
            'symfony/mailjet-mailer',
            'symfony/mailjet-notifier',
            'symfony/mattermost-notifier',
            'symfony/mercure-notifier',
            'symfony/message-bird-notifier',
            'symfony/message-media-notifier',
            'symfony/messenger',
            'symfony/microsoft-teams-notifier',
            'symfony/mime',
            'symfony/mobyt-notifier',
            'symfony/monolog-bridge',
            'symfony/notifier',
            'symfony/octopush-notifier',
            'symfony/oh-my-smtp-mailer',
            'symfony/one-signal-notifier',
            'symfony/options-resolver',
            'symfony/ovh-cloud-notifier',
            'symfony/password-hasher',
            'symfony/postmark-mailer',
            'symfony/process',
            'symfony/property-access',
            'symfony/property-info',
            'symfony/proxy-manager-bridge',
            'symfony/rate-limiter',
            'symfony/redis-messenger',
            'symfony/rocket-chat-notifier',
            'symfony/routing',
            'symfony/runtime',
            'symfony/security',
            'symfony/security-bundle',
            'symfony/security-core',
            'symfony/security-csrf',
            'symfony/security-http',
            'symfony/semaphore',
            'symfony/sendgrid-mailer',
            'symfony/sendinblue-mailer',
            'symfony/sendinblue-notifier',
            'symfony/serializer',
            'symfony/sinch-notifier',
            'symfony/slack-notifier',
            'symfony/sms-biuras-notifier',
            'symfony/sms77-notifier',
            'symfony/smsapi-notifier',
            'symfony/smsc-notifier',
            'symfony/spot-hit-notifier',
            'symfony/stopwatch',
            'symfony/string',
            'symfony/telegram-notifier',
            'symfony/telnyx-notifier',
            'symfony/templating',
            'symfony/translation',
            'symfony/turbo-sms-notifier',
            'symfony/twig-bridge',
            'symfony/twig-bundle',
            'symfony/twilio-notifier',
            'symfony/uid',
            'symfony/validator',
            'symfony/var-dumper',
            'symfony/var-exporter',
            'symfony/vonage-notifier',
            'symfony/web-link',
            'symfony/web-profiler-bundle',
            'symfony/workflow',
            'symfony/yaml',
            'symfony/yunpian-notifier',
            'symfony/zulip-notifier',
        ];
    }

    private static function corePackages(string $version)
    {
        static $packages = null;

        if (is_null($packages)) {
            $packages = json_decode(file_get_contents(__DIR__ . '/../data/laravel-core.json'), true);
        }

        return $packages[$version];
    }

    private static function communityPackages(string $version)
    {
        static $packages = null;

        if (is_null($packages)) {
            $packages = json_decode(file_get_contents(__DIR__ . '/../data/laravel-packages.json'), true);
        }

        return $packages[$version];
    }

    private static function tags()
    {
        static $packages = null;

        if (is_null($packages)) {
            $packages = json_decode(file_get_contents(__DIR__ . '/../data/laravel-tags.json'), true);
        }

        return $packages['tags'];
    }

    private static function pluckConstraints($packages, $constraint = 'latest'): array
    {
        $latest = [];

        foreach ($packages as $name => $attributes) {
            $latest[$name] = [
                'type' => $attributes['type'],
                'constraint' => $attributes['constraints'][$constraint] ?? $attributes['constraints']['latest'],
            ];
        }

        return $latest;
    }

    private static function verifyLaravelVersion(string $version)
    {
        if (!in_array($version, ['latest', '9.x', '8.x', '7.x', '6.x'])) {
            throw new \InvalidArgumentException(sprintf('Unexpected Laravel version (%s), version must be: latest, 9.x, 8.x, 7.x, or 6.x', $version));
        }
    }

    private static function verifyPhpVersion(?string $version)
    {
        if (is_null($version)) {
            return;
        }

        if (!in_array($version, ['8.1', '8.0', '7.4', '7.3'])) {
            throw new \InvalidArgumentException(sprintf('Unexpected PHP version (%s), version must be: 8.1, 8.0, 7.4, or 7.3', $version));
        }
    }
}
