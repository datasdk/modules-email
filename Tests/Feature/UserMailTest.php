<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Modules\Email\Models\Email;
use Modules\Email\Traits\TestsEmailTrait;
use Modules\Email\Database\Seeders\EmailDatabaseSeeder;
 

class UserMailTest extends TestCase
{

    use DatabaseMigrations;
    use TestsEmailTrait;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
            
        $this->seed(EmailDatabaseSeeder::class); 

        // Opret en testbruger
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_request_password_reset()
    {

        $response = $this->postJson(route('api.password.request'), [
            "email" => $this->user->email,
        ]);

        $response->assertStatus(200);

        $email = Email::firstOrFail();

        $this->assertEmailHasNoTemplateMarkers($email);

    }

    /** @test */
    public function user_can_resend_activation_email()
    {
        
        Artisan::call("db:seed");

        $response = $this->postJson(route("api.resend-activation-email"), [
            "email" => $this->user->email,
        ]);
    
        $response->assertStatus(200);

        $email = Email::firstOrFail();

        $this->assertEmailHasNoTemplateMarkers($email);

    }


    /** @test */
    public function user_can_resend_invitation()
    {
        
        Artisan::call("db:seed");

        $response = $this->postJson(route("api.resend-invitation"), [
            "email" => $this->user->email,
        ]);
        
        $response->assertStatus(200);
        
        $email = Email::firstOrFail();

        $this->assertEmailHasNoTemplateMarkers($email);


    }
    
}
