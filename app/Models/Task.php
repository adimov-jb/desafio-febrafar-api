<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type_id',
        'user_id',
        'description',
        'start_date',
        'deadline',
        'finish_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'finish_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

}
