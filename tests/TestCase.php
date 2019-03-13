<?php

namespace SaintSystems\Eloquent\Transformable\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
// use PHPUnit\Framework\TestCase as BaseTestCase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SaintSystems\Eloquent\Transformable\Transformable;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Route::middlewareGroup('nova', []);

        // $this->loadLaravelMigrations(['--database' => 'testbench']);

        // $this->createUsers();
    }

    // protected function getPackageProviders($app)
    // {
    //     return [
    //         CardServiceProvider::class,
    //     ];
    // }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    // protected function createUsers()
    // {
    //     User::forceCreate([
    //         'name' => 'Marcel',
    //         'email' => 'marcel@beyondco.de',
    //         'password' => 'test'
    //     ]);
    //     User::forceCreate([
    //         'name' => 'Sebastian',
    //         'email' => 'sebastian@beyondco.de',
    //         'password' => 'test'
    //     ]);
    // }
}
