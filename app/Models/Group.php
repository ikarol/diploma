<?php

namespace App\Models;


/**
 * @property integer $id
 * @property string $name
 * @property Student[] $students
 */
class Group extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student');
    }
}
