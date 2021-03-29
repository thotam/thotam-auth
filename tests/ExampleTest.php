<?php

namespace Thotam\ThotamAuth\Tests;

use Orchestra\Testbench\TestCase;
use Thotam\ThotamAuth\ThotamAuthServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ThotamAuthServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
