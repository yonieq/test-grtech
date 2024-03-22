<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $guarded = [];

    protected $appends = ['unique_id', 'value', 'label'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getLogoAttribute($value): string
    {
        return config('app.url') . '/storage/image/' . $value;
    }

    public function getUniqueIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }

    public function getValueAttribute()
    {
        return $this->id;
    }

    public function getLabelAttribute()
    {
        return $this->name;
    }
}