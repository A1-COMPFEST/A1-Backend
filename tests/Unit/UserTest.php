<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use RefreshDatabase;
    // use DatabaseMigrations;
    use DatabaseTransactions;
    /** @test */
   

    /** @test */
    public function it_has_fillable_attributes()
    {
        $user = new User();

        $this->assertEquals([
            'name',
            'email',
            'password',
            'token',
            'role',
            'balance'
        ], $user->getFillable());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $user = User::factory()->make([
            'password' => 'secret',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
    }

    
}