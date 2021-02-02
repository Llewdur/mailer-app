<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailStoreRequest;
use App\Http\Resources\EmailCollection;
use App\Http\Resources\EmailResource;
use App\Jobs\EmailJob;
use App\Models\Email;

class EmailController extends Controller
{
    public function index()
    {
        return new EmailCollection(Email::sent()->paginate());
    }

    public function store(EmailStoreRequest $request): EmailResource
    {
        $email = Email::create($request->all());

        EmailJob::dispatch($email)->onQueue('default');

        return new EmailResource($email);
    }
}
