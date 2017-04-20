<?php

namespace App\Models;


/**
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $surname
 * @property string $middlename
 * @property string $password
 * @property string $created_at
 * @property Professor $professor
 * @property Student $student
 */
class User extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['email', 'name', 'surname', 'middlename', 'password', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professor()
    {
        return $this->hasOne('App\Models\Professor', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'user_id');
    }
}
