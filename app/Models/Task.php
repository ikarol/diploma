<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $professor_id
 * @property integer $type
 * @property string $title
 * @property string $description
 * @property string $technologies
 * @property string $created_at
 * @property string $updated_at
 * @property Professor $professor
 * @property Request[] $requests
 * @property Discipline[] $disciplines
 */
class Task extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['professor_id', 'type', 'title', 'description', 'technologies', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professor()
    {
        return $this->belongsTo('App\Models\Professor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disciplines()
    {
        return $this->belongsToMany('App\Models\Discipline', 'tasks_disciplines');
    }
}
