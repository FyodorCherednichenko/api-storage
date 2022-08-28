<?php

namespace App\Models;

use App\QueryBuilders\UserFileQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $storage_id
 * @property int $file_size
 * @property string $path
 */
class UserFile extends Model
{
    use HasFactory;

    public function storage(): BelongsTo
    {
        return $this->belongsTo(UserStorage::class);
    }

    public function newEloquentBuilder($query): UserFileQueryBuilder
    {
        return new UserFileQueryBuilder($query);
    }
}
