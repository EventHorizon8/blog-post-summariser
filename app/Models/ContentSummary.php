<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentSummary
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $original_content
 * @property string $summary
 * @property int $token_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ContentSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'original_content',
        'summary',
        'token_count',
    ];

    public $timestamps = true;
}
