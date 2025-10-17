<?php

namespace Newnet\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'contact__labels';

    protected $fillable = [
        'name',
        'type',
    ];

    public function button() {
        return '<button type="button" class="btn btn-' . $this->type . '">' . $this->name . '</button>';
    }
}
