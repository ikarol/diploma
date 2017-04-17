<?php

namespace App\Models;

use App\Models\Diploma;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'surname', 'middlename', 'occupation', 'degree',
    ];
    public $timestamps = false;

    public function diploma()
    {
        $this->hasMany(Diploma::class);
    }
}
