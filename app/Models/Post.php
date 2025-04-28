<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): hasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): hasMany
    {
        return $this->hasMany(Like::class);
    }

    public function vocabularies(): hasMany
    {
        return $this->hasMany(Vocabulary::class);
    }

    public function tags(): belongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
