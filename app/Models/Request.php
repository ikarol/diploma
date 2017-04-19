<?php

namespace App\Models;

use App\Models\Model;

/**
 * @property integer $task_id
 * @property integer $student_id
 * @property integer $status
 * @property string $message
 * @property integer $mark
 * @property string $created_at
 * @property string $started_at
 * @property Task $task
 * @property Student $student
 */
class Request extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['status', 'message', 'mark', 'created_at', 'started_at'];

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
    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }
}
