<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_transfer_money()
    {
        $this->seed();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $account1 = Account::factory()->create([
            'user_id' => $user1->id,
            'balance' => 1000,
            'currency' => 'USD',
        ]);

        $account2 = Account::factory()->create([
            'user_id' => $user2->id,
            'balance' => 0,
            'currency' => 'USD',
        ]);

        Currency::create(['code' => 'USD', 'rate' => 1]);

        $response = $this->actingAs($user1)->postJson('/api/transfer', [
            'to_account_id' => $account2->id,
            'amount' => 500,
            'currency' => 'USD',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('accounts', [
            'id' => $account1->id,
            'balance' => 500,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account2->id,
            'balance' => 500,
        ]);
    }
}
