<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'value', 'card_id'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
