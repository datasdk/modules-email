<?php

namespace Modules\Email\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Modules\Email\Models\MailServer;
use Illuminate\Support\Facades\Artisan;
use Modules\Email\Models\MailTemplates;
use Database\Seeders\TransactionStatusSeeder;
use Modules\Email\Database\Seeders\EmailDatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Modules\Email\Models\Email;
use Modules\Email\Traits\TestsEmailTrait;



class EmailSMTPTest extends TestCase
{

    use DatabaseMigrations;
    use TestsEmailTrait;

    
    protected function setUp(): void
    {
        parent::setUp();
            
        $this->seed(EmailDatabaseSeeder::class); 

    }

    public function test_show_smtp_settings()
    {

        $user = User::factory()->create();
    
        // Create a mail server entry so getMailConfig() can return something
        MailServer::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.email.settings.smtp.show'));

        $response->assertStatus(200);

    }


    public function test_update_smtp_settings_success()
    {


        $user = User::factory()->create();
   
        $payload = [
            'default' => 'smtp',
            'mailers' => [
                'smtp' => [
                    'host' => 'smtp.example.com',
                    'port' => 587,
                    'username' => 'user@example.com',
                    'password' => 'secret',
                    'encryption' => 'tls',
                ]
            ],
            'from' => [
                'name' => 'Test Sender',
                'address' => 'sender@example.com',
                'reply_address' => 'reply@example.com'
            ],
            'ACTIVE' => true,
        ];

        $response = $this->actingAs($user)->patchJson(route('api.email.settings.smtp.update'), $payload);

        $response->assertStatus(200)
                 ->assertSee('SMTP settings updated successfully');

    
    }


    public function test_template_seeder()
    {
        // Run the seeder
        $this->seed(EmailDatabaseSeeder::class);

        // Assert that at least one record was created in the MailServer table
        $this->assertGreaterThan(0, MailTemplates::count());
    }



    public function test_send_test_mail_smtp()
    {


        $user = User::factory()->create();

        // Create a mail server entry
        MailServer::factory()->create();

        // Manually run the seeder outside of the test's transaction lifecycle
       // Artisan::call('db:seed');
       $this->seed(EmailDatabaseSeeder::class);
        
     
        // Test sending a test email after seeding
        $payload = ['email' => 'test@example.com'];

        $response = $this->actingAs($user)->postJson(route('api.email.settings.smtp.send_test_mail'), $payload);

        $response->assertStatus(200);

        $email = Email::firstOrFail();

        $this->assertEmailHasNoTemplateMarkers($email);

    }

}
