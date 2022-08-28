<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $storage_size
 * @property string $root_path
 *
 */
class UserStorage extends Model
{
    use HasFactory;

    public const TABLE = 'user_storages';

    protected $table = self::TABLE;

    public $timestamps = false;

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class);
    }
}
