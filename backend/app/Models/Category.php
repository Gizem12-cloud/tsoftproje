<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'is_active'];

    
    public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}


public function descendantIds(): array
{
    $ids = [];

    foreach ($this->children as $child) {
        $ids[] = $child->id;
        $ids = array_merge($ids, $child->descendantIds());
    }

    return $ids;
}

}
