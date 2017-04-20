<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $group_id
 * @property User $user
 * @property Group $group
 * @property Request[] $requests
 */
class Student extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'group_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }
}
