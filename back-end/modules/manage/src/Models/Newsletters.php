<?php

namespace Modules\Manage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Manage\Models\Newsletters
 *
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property string $status
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters query()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletters whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Newsletters extends Model
{
  use HasFactory;

  protected $table = 'manage__newsletters';

  protected $fillable = [
    'email',
    'name',
    'status',
    'message',
  ];
}
