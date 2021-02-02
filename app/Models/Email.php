<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    // protected $cast = [
    //     'attachments' => 'array',
    // ];

    protected $fillable = [
        'attachments',
        'body',
        'subject',
        'to',
    ];

    public function getAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = json_encode($value);
    }

    public function scopeNotSent($query)
    {
        return $query->where('is_sent', 0);
    }

    public function scopeSent($query)
    {
        return $query->where('is_sent', 1);
    }

    public function setIsSent(): self
    {
        $this->is_sent = 1;

        return $this;
    }
}
