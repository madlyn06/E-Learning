<?php

namespace Newnet\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact__contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'content',
        'source',
        'is_handle',
        'note',
        'label_id',
    ];

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
