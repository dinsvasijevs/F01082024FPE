<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_transfer_money()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $account1 = Account::factory()->create([
            'user_id' => $user1->id,
            'balance' => 1000,
            'currency' => 'USD'
        ]);

        $account2 = Account::factory()->create([
            'user_id' => $user2->id,
            'balance' => 0,
            'currency' => 'USD'
        ]);

        $response = $this->actingAs($user1)->post('/transfer', [
            'to_account_id' => $account2->id,
            'amount' => 500,
            'currency' => 'USD'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertEquals(500, $account1->fresh()->balance);
        $this->assertEquals(500, $account2->fresh()->balance);
    }

    public function test_user_cannot_transfer_more_than_balance()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $account1 = Account::factory()->create([
            'user_id' => $user1->id,
            'balance' => 100,
            'currency' => 'USD'
        ]);

        $account2 = Account::factory()->create([
            'user_id' => $user2->id,
            'balance' => 0,
            'currency' => 'USD'
        ]);

        $response = $this->actingAs($user1)->post('/transfer', [
            'to_account_id' => $account2->id,
            'amount' => 500,
            'currency' => 'USD'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');

        $this->assertEquals(100, $account1->fresh()->balance);
        $this->assertEquals(0, $account2->fresh()->balance);
    }
}
