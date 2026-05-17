<?php

namespace Modules\Email\Http\Controllers;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Modules\Email\Models\Email;
use Modules\Email\Http\Requests\EmailRequest;
use Modules\Email\Services\EmailService;
use App\Models\User;
use Carbon\Carbon;


class EmailController extends OrionBaseController
{
    protected $model = Email::class;
    protected $request = EmailRequest::class;

    protected $includes = [
        'categories'
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



    public function index(Request $request)
    {
        // Hent alle e-mails, evt. med pagination
        $emails = Email::with(['template'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Returner view med e-mails
        return view('email::emails.index', compact('emails'));
    }

    /**
     * Web: vis enkelt e-mail
     */
    public function show(Request $req, ...$args)
    {

        $email = Email::with(['template', 'media'])
            ->where('uuid', $args[0])
            ->firstOrFail();


        $user = $req->user();


        // Kun ejeren eller admin kan se mailen
        if ($user && ($email->user_id == $user->id || $user->isAdmin())) {

            return view('email::emails.show', compact('email'));

        }

        abort(404);

    }

    /**
     * Overstyr store for web-specifik logik
     */
    public function store(Request $req)
    {

        $data = $req->validated();


        if ($req->has('user_id')) {

            $user = User::findOrFail($req->user_id);

            $data['to'] = $user->email;

        }


        $email = app(EmailService::class)->send($data);


        if (!$email) {

            return redirect()->back()->withErrors(['message' => 'E-mail not sent']);

        }


        return redirect()->route('emails.index')->with('success', 'E-mail sent successfully!');

    }


    /**
     * Overstyr update for web
     */
    public function update(Request $req, ...$args)
    {

        $email = Email::findOrFail($args[0]);

        $data = $req->validated();


        if (isset($data['send_after']) && !$data['send_after']) {

            $data['send_after'] = Carbon::now();

        }


        $email = app(EmailService::class)->update($email, $data);

        return redirect()->route('emails.index')->with('success', 'E-mail updated successfully!');

    }


    /**
     * Overstyr destroy for web
     */
    public function destroy(Request $req, ...$args)
    {

        $email = Email::findOrFail($args[0]);

        app(EmailService::class)->delete($email);

        return redirect()->route('emails.index')->with('success', 'E-mail deleted successfully!');


    }

}
