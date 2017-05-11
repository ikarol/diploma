<?php

namespace App\Models;

use App\Models\Model;

/**
 * @property integer $id
 * @property string $name
 * @property Student[] $students
 * @property Task[] $tasks
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
