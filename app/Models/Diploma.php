<?php

namespace App\Models;

use App\Models\Professor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Diploma extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'technologies',
        'student_id',
        'professor_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function getDates()
    {
        // only this field will be converted to Carbon
        return [];
    }

    public static function archives()
    {
        return static::selectRaw('year(created_at) as year,
                right(\'0\' + RTRIM(MONTH(created_at)),2) as month,
                     count(*) as published')
                        ->groupBy(DB::raw('year(created_at),
                            right(\'0\' + RTRIM(MONTH(created_at)),2)'))
                                ->get()->toArray();
    }
}
