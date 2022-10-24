<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $fillable = ['category_id', 'title', 'content', 'file_id', 'status', 'created_at', 'updated_at'];

    public function file()
    {
        return $this->belongsTo(File::class,'file_id','id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
