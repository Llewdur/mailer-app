<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        Mail::to($this->email->to)
            ->send(new GenericMail($this->email));

        $this->email->setIsSent()->save();
    }
}
