<?php

namespace Modules\Email\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Modules\Email\Http\Resources\BaseResource;
use Modules\Email\Services\EmailService;
use Modules\Email\Models\Email;
use Modules\Email\Http\Requests\EmailRequest;
use User;

class EmailController extends OrionBaseController
{

    protected $model = Email::class;
    //protected $resource = BaseResource::class;
    protected $request = EmailRequest::class;

    


    protected $includes = [
        "categories"
    ];

    protected $filterableBy = [
        'send_after'
    ];

    protected $sortableBy = [
        'id',
        'send_after',
        'to',
        'created_at'
    ];

    protected $searchableBy = [
        'subject',
        'message',
        'to',
        'send_after',
    ];


    /**
     * Opretter en ny e-mail og sender den.
     */
    public function store(Request $req)
    {
   
        $data = $req->validated();

        
        if($req->has("user_id")){

           $user = User::findOrFail($req->user_id);

           $data["to"] = $user->email;

        }



        $email = app(EmailService::class)->send($data);


        if(!$email){

            return response()->json(['error' => 'E-mail not sent'], 400);

        }


        return response()->json($email);

    }

    /**
     * Opdaterer en eksisterende e-mail.
     */
    public function update(Request $req, ...$args)
    {
        
        $id = $args[0];

        $email = Email::findOrFail($id);

        $data = $req->validated();


        if (empty($data)) {

            return response()->json(['error' => 'No data provided'], 400);

        }
        

        if(isset($data["send_after"]) && !$data["send_after"]){ $data["send_after"] = Carbon::now(); }

        
        $email = app(EmailService::class)->update($email, $data);
        

        return response()->json($email);

    }


    /**
     * Sletter en e-mail.
     */
    public function destroy(Request $req, ...$args)
    {
        
        $id = $args[0];

        $email = Email::findOrFail($id);

        app(EmailService::class)->delete($email);

        // Returner tomt svar med 204 No Content
        return response()->noContent();

    }



    public function hasIgnoreOption(Request $req, string $option){


        if($req->has("ignore")){

            return in_array("is_already_sent",$req->ignore);

        }


    }
    
}
