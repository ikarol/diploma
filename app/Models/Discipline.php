<?php

namespace App\Models;


/**
 * @property integer $id
 * @property string $name
 * @property Task[] $tasks
 */
class Discipline extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task', 'tasks_disciplines');
    }
}
