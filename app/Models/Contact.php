<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Contact $contact) {
            $contact->metadata = [];
        });
    }

    //scopes

    public function scopePending($query){
        return $query->where('status', 'pending');
    }

    public function scopeProcessed($query){
        return $query->where('status', 'processed');
    }

    //Accessors // formatte le nom afin d'avoir le premier caractere du nom en majuscule
    protected function name(): Attribute 
    {
        return Attribute::make(
            set: fn (string $value) => ucwords(strtolower(trim($value)))
        );
    }

    // transforme l'email en minuscule
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower(trim($value))
        );
    }
}
