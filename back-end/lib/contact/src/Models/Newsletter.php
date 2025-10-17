<?php


namespace Newnet\Contact\Models;


use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table= 'contact__newsletter';
    protected $fillable = ['email', 'type', 'black_book'];

}
