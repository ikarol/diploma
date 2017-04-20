<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $user_id
 * @property string $occupation
 * @property string $degree
 * @property User $user
 * @property Task[] $tasks
 */
class Professor extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'occupation', 'degree'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
