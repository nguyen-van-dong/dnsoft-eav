<?php

namespace Dnsoft\Eav\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeEntity extends Model
{
    protected $table = 'attribute_type';

    protected $fillable = [
        'attribute_id',
        'name',
        'slug',
    ];
}
