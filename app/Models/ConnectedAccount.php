<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ConnectedAccountFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $identifier
 * @property string $service
 *
 * @property-read User $user
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ConnectedAccount extends Model
{
    /** @use HasFactory<ConnectedAccountFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'user_id',
        'identifier',
        'service',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
