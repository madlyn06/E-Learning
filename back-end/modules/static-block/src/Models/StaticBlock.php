<?php

namespace Modules\StaticBlock\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StaticBlock extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'staticblock__static_blocks';

    protected $fillable = [
        'name',
        'description',
        'content',
        'slug',
        'css',
        'script',
        'is_active',
        'image',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getUrl()
    {
        return route('staticblock.web.static-block.detail', $this->id);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    // public function setSlugAttribute($value): void
    // {
    //     dd($this->name);
    //     $this->slug = Str::slug($this->name);
    // }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ($value),
            set: fn (string $value) => Str::slug($this->name),
        );
    }
}
