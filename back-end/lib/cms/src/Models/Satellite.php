<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

class Satellite extends Model
{
    use HasMediaTrait;
    use TranslatableTrait;

    protected $table = 'cms__satellites';

    protected $fillable = [
        'name',
        'url',
        'category_id',
        'description',
        'is_active',
        'image',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function author()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }
}
