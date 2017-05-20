<?php

namespace App\Models;

use App\Models\Model;

/**
 * @property integer $task_id
 * @property string $description
 * @property string $created_at
 * @property string $deadline
 * @property Task $task
 */
class Job extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['task_id', 'description', 'created_at', 'deadline'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
