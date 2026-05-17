<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Email\Database\Seeders\MailTemplateParamsSeeder;


class EmailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            EmailTemplateSeeder::class,
    
            MailTemplateParamsSeeder::class
        ]);

    }
}
