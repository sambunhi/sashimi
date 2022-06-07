<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $published_at
 */
class Article extends Model
{
    use HasFactory;

    protected $dates = [
        'published_at',
    ];

    protected $fillable = ['title', 'url', 'source_id', 'published_at'];

    public function trend()
    {
        return $this->hasMany(Trend::class, 'article_id', 'id')->select('article_id', 'keyword', 'cnt');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id')->select('id', 'name');
    }
}
