<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'id',
        'title',
        'content',
        'image',
        'short_description',
        'posted_at',
        'remove_at',
        'view_count',
        'status',
        'seo_title',
        'seo_description',
        'catalogue_id',
        'slug',
        

    ];

    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function blogTags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'blog_keyword');
    }


}