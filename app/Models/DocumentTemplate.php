<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle de document publié par l’administration, téléchargeable par tout utilisateur connecté.
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $original_filename
 * @property string $disk
 * @property string $storage_path
 * @property string|null $mime_type
 * @property int|null $size_bytes
 * @property int $sort_order
 * @property int|null $uploaded_by_user_id
 */
class DocumentTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'original_filename',
        'disk',
        'storage_path',
        'mime_type',
        'size_bytes',
        'sort_order',
        'uploaded_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
