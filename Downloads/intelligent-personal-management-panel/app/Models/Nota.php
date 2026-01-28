<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'contenido',
        'tags',
        'archivada',
    ];

    protected $casts = [
        'archivada' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function archivar()
    {
        $this->update(['archivada' => true]);
    }

    public function desarchivar()
    {
        $this->update(['archivada' => false]);
    }

    public function getTags()
    {
        if (!$this->tags) {
            return [];
        }
        return explode(',', $this->tags);
    }

    public function setTags(array $tags)
    {
        $this->tags = implode(',', $tags);
    }
}
