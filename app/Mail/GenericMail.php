<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    protected Email $email;

    protected $type;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function build()
    {
        $self = $this->markdown('emails.blank')
            ->subject($this->email->subject)
            ->with([
                'body' => $this->email->body,
            ]);

        return $this->setAttachData($self);
    }

    private function setAttachData($self)
    {
        if (blank($this->email->attachments)) {
            return $self;
        }

        foreach ($this->email->attachments as $attachment) {
            $self->attachData(base64_decode($attachment->base64, true), $attachment->name);
        }

        return $self;
    }
}
