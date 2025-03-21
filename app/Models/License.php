<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'license_key', 'expired_at', 'is_active'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isValid(): bool
    {
        return $this->is_active && Carbon::now()->lessThanOrEqualTo($this->expired_at);
    }
}
