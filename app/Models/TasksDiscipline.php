<?php

namespace App\Models;


/**
 * @property integer $task_id
 * @property integer $discipline_id
 * @property Task $task
 * @property Discipline $discipline
 */
class TasksDiscipline extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['task_id', 'discipline_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo('App\Models\Discipline');
    }
}
