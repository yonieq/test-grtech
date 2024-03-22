<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $guarded = [];

    protected $appends = ['unique_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getUniqueIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }
}