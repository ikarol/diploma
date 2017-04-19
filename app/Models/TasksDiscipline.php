<?php

namespace App\Models;

use App\Models\Model;

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
    protected $fillable = [];

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
