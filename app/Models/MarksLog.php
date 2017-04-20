<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $task_id
 * @property integer $old_value
 * @property integer $new_value
 * @property string $updated_at
 */
class MarksLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['task_id', 'old_value', 'new_value', 'updated_at'];

}
