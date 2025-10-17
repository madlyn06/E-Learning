<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

class Section extends Model
{
    use TranslatableTrait;
    use SeoableTrait;

    protected $table = 'elearning__sections';

    protected $fillable = [
        'name',
        'description',
        'content',
        'is_active',
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
        return route('elearning.web.section.detail', $this->id);
    }
}
