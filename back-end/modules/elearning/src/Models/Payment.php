<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $reference_id
 * @property int $user_id
 * @property int|null $course_id
 * @property int|null $membership_id
 * @property int $payment_method_id
 * @property numeric $amount
 * @property string $currency
 * @property string $status
 * @property array<array-key, mixed>|null $transaction_data
 * @property string|null $transaction_id
 * @property string|null $payment_url
 * @property string|null $qr_code
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course|null $course
 * @property-read \Modules\Elearning\Models\MemberShip|null $membership
 * @property-read \Modules\Elearning\Models\PaymentMethod $paymentMethod
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'elearning__payments';

    protected $fillable = [
        'reference_id',
        'user_id',
        'course_id',
        'membership_id',
        'payment_method_id',
        'amount',
        'currency',
        'status',
        'transaction_data',
        'transaction_id',
        'payment_url',
        'qr_code',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for completed payments
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope for failed payments
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Generate a unique reference ID
    public static function generateReferenceId()
    {
        return 'PAY-' . strtoupper(uniqid()) . '-' . time();
    }
}
