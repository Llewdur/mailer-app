<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attachments' => 'nullable|array',
            'attachments.*' => 'required_if:exists,attachments|array|min:2|max:2|distinct',
            'attachments.*.name' => 'required_if:exists,attachments|string|min:1',
            'attachments.*.base64' => 'required_if:exists,attachments|string|min:1',
            'body' => 'required|string|min:1',
            'subject' => 'required|string|min:1|max:255',
            'to' => 'required|string|min:1|max:255',
        ];
    }
}
