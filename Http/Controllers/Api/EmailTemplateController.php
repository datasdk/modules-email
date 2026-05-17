<?php

namespace Modules\Email\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request; 
use Modules\Email\Http\Requests\EmailTemplateRequest;
use Modules\Email\Models\MailTemplates;
use App\Mail\Standard;
use Exception;

class EmailTemplateController extends OrionBaseController
{
    protected $model = MailTemplates::class;
    protected $request = EmailTemplateRequest::class;

    protected $includes = [
        "attachments",
        "categories",
    ];

    protected $sortableBy = [
        "subject",
    ];

    protected $searchableBy = [
        "subject",
        "html_template",
        "categories.name",
    ];

    /**
     * Opret en ny e-mail skabelon
     */
    public function store(Request $req)
    {
        
        try {

            $email = MailTemplates::create(
                $req->only([
                    "subject",
                    "html_template",
                    "text_template",
                ]) + [
                    "mailable" => Standard::class
                ]
            )->setCategories($req->categories);

            return response()->json($email, 201);


        } catch (Exception $e) {

            return response()->json([
                "error" => "Failed to create email template",
                "details" => $e->getMessage()
            ], 500);

        }

    }

    /**
     * Opdater en eksisterende e-mail skabelon
     */
    public function update(Request $req, ...$args)
    {

        try {
            
            $id = $args[0];


            $email = MailTemplates::findOrFail($id);


            $email->update(
                $req->only([
                    "subject",
                    "html_template",
                    "text_template",
                    "active"
                ])
            );


            if ($req->has("categories")) {
                $email->setCategories($req->categories);
            }


            return $email;


        } catch (Exception $e) {

            return response()->json([
                "error" => "Failed to update email template",
                "details" => $e->getMessage()
            ], 500);

        }

    }

}
