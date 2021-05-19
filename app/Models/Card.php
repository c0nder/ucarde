<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'title', 'username', 'card_id', 'public_id'];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function hasField(Field $field)
    {
        return $this->fields->contains($field);
    }

    public function withFields()
    {
        return $this->load('fields');
    }
}
