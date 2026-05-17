<?php

namespace Modules\Email\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Modules\Email\Models\Email;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Modules\Email\Database\Seeders\EmailDatabaseSeeder;


class EmailTest extends TestCase
{
    use DatabaseMigrations;


    protected function setUp(): void
    {
        parent::setUp();
            
        $this->seed(EmailDatabaseSeeder::class); 

    }

    public function test_store_email()
    {

        $user = User::factory()->create();
    

        $payload = [
            'subject' => 'Test Email',
            'message' => 'This is a test email',
            'user_id' => $user->id
            // Tilføj evt. yderligere felter, som EmailRequest kræver
        ];

        $response = $this->actingAs($user)->postJson(route('api.email.emails.store'), $payload);
        $response->assertStatus(200);
    }

    public function test_update_email()
    {

        $user = User::factory()->create();


        $email = Email::factory()->create([
            'subject' => 'Old Subject',
            'message' => 'Old message'
        ]);

        $payload = [
            'subject' => 'Updated Subject',
            'message' => 'Updated message'
        ];

        $response = $this->actingAs($user)->putJson(route('api.email.emails.update', $email->id), $payload);

        $response->assertStatus(200);


    }

    public function test_delete_email()
    {
        
        $user = User::factory()->create();

        $email = Email::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('api.email.emails.destroy', $email->id));

        $response->assertNoContent();
     

    }

}
