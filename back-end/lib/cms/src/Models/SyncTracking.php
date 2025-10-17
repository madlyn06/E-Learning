<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Cms\Models\SyncTracking
 *
 * @property int $id
 * @property string $status
 * @property string $message
 * @property int $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTracking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SyncTracking extends Model
{
  use HasFactory;

  protected $table = 'cms__sync_trackings';

  protected $fillable = [
    'status',
    'message',
    'processed',
  ];
}
