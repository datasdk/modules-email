<?php

namespace Modules\Email\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Modules\Email\Models\MailTemplates;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Email\Models\Email;
use Modules\Email\Traits\TestsEmailTrait;
use Modules\Email\Database\Seeders\EmailDatabaseSeeder;
        

class EmailTemplateTest extends TestCase
{

    use DatabaseMigrations;
    use TestsEmailTrait;

   
    protected function setUp(): void
    {
        parent::setUp();
            
        $this->seed(EmailDatabaseSeeder::class); 

    }


    public function test_store_email_template()
    {
        $user = User::factory()->create();
 

        $payload = [
            'subject' => ['en' => 'Test Subject'],
            'html_template' => ['en' => '<p>Test HTML</p>'],
            'text_template' => ['en' => 'Test Text']
            // Vedhæftninger er valgfri, tilføj dem evt. hvis nødvendigt
        ];

        $response = $this->actingAs($user)->postJson(route('api.email.templates.store'), $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('mail_templates', ['subject' => json_encode($payload['subject'])]);

    }


    public function test_update_email_template()
    {

        $user = User::factory()->create();
    

        $template = MailTemplates::factory()->create([
            'subject' => ['en' => 'Old Subject'],
            'html_template' => ['en' => '<p>Old HTML</p>'],
            'text_template' => ['en' => 'Old Text']
        ]);

        $payload = [
            'subject' => ['en' => 'New Subject'],
            'html_template' => ['en' => '<p>New HTML</p>'],
            'text_template' => ['en' => 'New Text']
        ];

        $response = $this->actingAs($user)->putJson(route('api.email.templates.update', $template->id), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mail_templates', ['subject' => json_encode($payload['subject'])]);


    }


   
}
