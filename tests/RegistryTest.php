<?php

namespace Tests;

use Shift\Packages\Registry;

class RegistryTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_returns_latest_version_of_core_packages_for_laravel()
    {
        $packages = Registry::corePackagesFor('latest');

        $this->assertSame(['type' => 'require', 'constraint' => '^8.0'], $packages['php']);
        $this->assertSame(['type' => 'require', 'constraint' => '^2.5'], $packages['laravel/tinker']);
        $this->assertSame(['type' => 'require-dev', 'constraint' => '^1.9.1'], $packages['fakerphp/faker']);
    }

    /** @test */
    public function it_returns_specific_version_of_core_packages_for_laravel()
    {
        $packages = Registry::corePackagesFor('6.x');

        $this->assertSame(['type' => 'require', 'constraint' => '^7.2|^8.0'], $packages['php']);
        $this->assertSame(['type' => 'require', 'constraint' => '^2.5'], $packages['laravel/tinker']);
        $this->assertSame(['type' => 'require-dev', 'constraint' => '^8.5.8|^9.3.3'], $packages['phpunit/phpunit']);
    }

    /** @test */
    public function it_returns_latest_versions_of_community_packages_for_laravel_version()
    {
        $packages = Registry::communityPackagesFor('latest');

        $this->assertSame(['type' => 'require-dev', 'constraint' => '^2.1'], $packages['jasonmccreary/laravel-test-assertions']);
        $this->assertSame(['type' => 'require', 'constraint' => '^5.5'], $packages['spatie/laravel-permission']);
    }

    /** @test */
    public function it_returns_earliest_versions_of_community_packages_for_laravel_version()
    {
        $packages = Registry::communityPackagesFor('latest', null, 'earliest');

        $this->assertSame(['type' => 'require-dev', 'constraint' => '^2.1'], $packages['jasonmccreary/laravel-test-assertions']);
        $this->assertSame(['type' => 'require', 'constraint' => '^5.5'], $packages['spatie/laravel-permission']);
    }

    /** @test */
    public function it_returns_latest_versions_of_community_packages_for_php_version()
    {
        $packages = Registry::communityPackagesFor('8.x', '8.0');

        $this->assertSame(['type' => 'require-dev', 'constraint' => '^2.1'], $packages['jasonmccreary/laravel-test-assertions']);
        $this->assertSame(['type' => 'require', 'constraint' => '^5.5'], $packages['spatie/laravel-permission']);
    }

    /** @test */
    public function it_returns_earliest_versions_of_community_packages_for_php_version()
    {
        $packages = Registry::communityPackagesFor('8.x', '8.1', 'earliest');

        $this->assertSame(['type' => 'require-dev', 'constraint' => '^0.1'], $packages['jasonmccreary/laravel-test-assertions']);
        $this->assertSame(['type' => 'require', 'constraint' => '^3.18'], $packages['spatie/laravel-permission']);
    }

    /** @test */
    public function it_returns_specific_version_of_community_packages_for_laravel_version()
    {
        $packages = Registry::communityPackagesFor('6.x');

        $this->assertSame(['type' => 'require-dev', 'constraint' => '^1.1'], $packages['jasonmccreary/laravel-test-assertions']);
        $this->assertSame(['type' => 'require', 'constraint' => '^5.3'], $packages['spatie/laravel-permission']);
    }

    public function it_returns_laravel_version_for_series()
    {
        $this->assertSame('9.30', Registry::tagForSeries());
        $this->assertSame('9.30', Registry::tagForSeries('latest'));
        $this->assertSame('9.30', Registry::tagForSeries('lts'));
    }

    public function it_returns_symfony_version_for_laravel()
    {
        $this->assertSame('^6.0', Registry::symfonyConstraintFor('latest'));
        $this->assertSame('^6.0', Registry::symfonyConstraintFor('9.x'));
        $this->assertSame('^5.4', Registry::symfonyConstraintFor('8.x'));
        $this->assertSame('^5.0', Registry::symfonyConstraintFor('7.x'));
        $this->assertSame('^4.3.4', Registry::symfonyConstraintFor('6.x'));
    }
}
