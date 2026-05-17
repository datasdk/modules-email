<?php

namespace Modules\Email\Http\Controllers;

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





    public function index(Request $request)
    {

        // Hent alle e-mails, evt. med pagination
        $template = MailTemplates::paginate(20);


        // Returner view med e-mails
        return view('email::templates.index', compact('template'));

    }


    public function edit(Request $request,...$args)
    {

        $template = MailTemplates::findOrFail($args[0]);

        return view('email::templates.edit', compact('template'));

    }

    /**
     * Web: vis en enkelt template
     */
    public function show(Request $req, ...$args)
    {

        $template = MailTemplates::with(['attachments', 'categories'])->findOrFail($args[0]);

        return view('email::templates.show', compact('template'));
    }

    /**
     * Web: opret ny template
     */
    public function store(Request $req)
    {

        $data = $req->validated();

        try {

            $template = MailTemplates::create(
                $data + ["mailable" => Standard::class]
            )->setCategories($data['categories'] ?? []);

            return redirect()->route('templates.index')
                ->with('success', 'Email template created successfully!');


        } catch (Exception $e) {

            return redirect()->back()
                ->withErrors(['error' => 'Failed to create email template: ' . $e->getMessage()])
                ->withInput();

        }

    }

    /**
     * Web: opdater template
     */
    public function update(Request $req, ...$args)
    {

        $template = MailTemplates::findOrFail($args[0]);

        $data = $req->validated();


        try {

            $template->update($data);

            if (isset($data['categories'])) {
                $template->setCategories($data['categories']);
            }


            return redirect()->route('templates.index')
                ->with('success', 'Email template updated successfully!');


        } catch (Exception $e) {

            return redirect()->back()
                ->withErrors(['error' => 'Failed to update email template: ' . $e->getMessage()])
                ->withInput();

        }

    }


    /**
     * Web: slet template
     */
    public function destroy(Request $req, ...$args)
    {

        $template = MailTemplates::findOrFail($args[0]);


        try {

            $template->delete();

            return redirect()->route('templates.index')
                ->with('success', 'Email template deleted successfully!');

        } catch (Exception $e) {

            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete template: ' . $e->getMessage()]);

        }

    }

}
