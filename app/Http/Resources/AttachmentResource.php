<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'download_url' => $this->name,
        ];
    }
}
