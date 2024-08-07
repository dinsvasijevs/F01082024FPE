<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_account_is_created_when_user_registers()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        $account = $user->account;
        $this->assertNotNull($account);
        $this->assertEquals(0, $account->balance);
        $this->assertEquals('USD', $account->currency);
    }
}
