<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'attachments' => new AttachmentCollection(optional($this->attachments)),
            'body' => $this->body,
            'subject' => $this->subject,
            'to' => $this->to,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
