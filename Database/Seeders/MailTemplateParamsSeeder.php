<?php

namespace Modules\Email\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class MailTemplateParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        if(!Schema::hasTable("mail_template_params")){ return; }

        // Disable foreign key checks to avoid constraint issues
        Schema::disableForeignKeyConstraints();
        
        // Truncate table before inserting
        DB::table('mail_template_params')->truncate();
        
        
        // Data to insert
        $data = [
            'user.first_name' => 'User First Name',
            'user.last_name' => 'User Last Name',
            'user.email' => 'User Email',
            'user.updated_at' => 'User Updated At',
            'user.created_at' => 'User Created At',
            'user.id' => 'User Id',
            'user.lastLogin' => 'User LastLogin',
            'user.online' => 'User Online',
            'url' => 'Url',
            'user.addresses' => 'User Addresses',
            'user.type' => 'User Type',
            'task.id' => 'Task Id',
            'task.uid' => 'Task Uid',
            'task.type' => 'Task Type',
            'task.name' => 'Task Name',
            'task.slug' => 'Task Slug',
            'task.resume' => 'Task Resume',
            'task.description' => 'Task Description',
            'task.label' => 'Task Label',
            'task.price' => 'Task Price',
            'task.link' => 'Task Link',
            'task.user_id' => 'Task User Id',
            'task.access' => 'Task Access',
            'task.company_id' => 'Task Company Id',
            'task.amount' => 'Task Amount',
            'task.sorting' => 'Task Sorting',
            'task.status' => 'Task Status',
            'task.active' => 'Task Active',
            'task.settings' => 'Task Settings',
            'task.deleted_at' => 'Task Deleted At',
            'task.created_at' => 'Task Created At',
            'task.updated_at' => 'Task Updated At',
            'task.matches_count' => 'Task Matches Count',
            'task.hires_count' => 'Task Hires Count',
            'task.averageRating' => 'Task AverageRating',
            'task.tags.0' => 'Task Tags 0',
            'task.tags.1' => 'Task Tags 1',
            'task.matchId' => 'Task MatchId',
            'task.images' => 'Task Images',
            'task.documents.0.id' => 'Task Documents 0 Id',
            'task.documents.0.type' => 'Task Documents 0 Type',
            'task.documents.0.collection' => 'Task Documents 0 Collection',
            'task.documents.0.size' => 'Task Documents 0 Size',
            'task.documents.0.created_at' => 'Task Documents 0 Created At',
            'task.documents.0.url' => 'Task Documents 0 Url',
            'task.user.id' => 'Task User Id',
            'task.user.uid' => 'Task User Uid',
            'task.user.image' => 'Task User Image',
            'task.user.username' => 'Task User Username',
            'task.user.first_name' => 'Task User First Name',
            'task.user.middle_name' => 'Task User Middle Name',
            'task.user.last_name' => 'Task User Last Name',
            'task.user.email' => 'Task User Email',
            'task.user.type' => 'Task User Type',
            'task.user.created_at' => 'Task User Created At',
            'task.user.updated_at' => 'Task User Updated At',
            'task.user.isRatedByMe' => 'Task User IsRatedByMe',
            'task.user.isFavoritedByMe' => 'Task User IsFavoritedByMe',
            'task.user.averageRating' => 'Task User AverageRating',
            'task.user.averageRatingAllTypes' => 'Task User AverageRatingAllTypes',
            'task.user.favoritersCount' => 'Task User FavoritersCount',
            'task.user.contact.id' => 'Task User Contact Id',
            'task.user.contact.type' => 'Task User Contact Type',
            'task.user.contact.first_name' => 'Task User Contact First Name',
            'task.user.contact.middle_name' => 'Task User Contact Middle Name',
            'task.user.contact.last_name' => 'Task User Contact Last Name',
            'task.user.contact.company' => 'Task User Contact Company',
            'task.tags.requirements.0' => 'Task Tags Requirements 0',
            'task.tags.requirements.1' => 'Task Tags Requirements 1',
            'task.files' => 'Task Files',
            'task.user.contact.vat_id' => 'Task User Contact Vat Id',
            'task.user.contact.position' => 'Task User Contact Position',
            'task.user.contact.phone' => 'Task User Contact Phone',
            'task.user.contact.mobile' => 'Task User Contact Mobile',
            'task.user.contact.fax' => 'Task User Contact Fax',
            'task.user.contact.email' => 'Task User Contact Email',
            'task.user.contact.website' => 'Task User Contact Website',
            'task.user.contact.address_id' => 'Task User Contact Address Id',
            'task.user.contact.contactable_type' => 'Task User Contact Contactable Type',
            'task.user.contact.contactable_id' => 'Task User Contact Contactable Id',
            'task.user.contact.is_public' => 'Task User Contact Is Public'
        ];
        
        
        // Add timestamps
        foreach ($data as $name => $label) {
            // Insert data
            DB::table('mail_template_params')->insert([
                "template_id" => 0,
                "name" => $name,
                "label" => $label
            ]);

        }
         
        
        // Enable foreign key checks again
        Schema::enableForeignKeyConstraints();

    }
}
