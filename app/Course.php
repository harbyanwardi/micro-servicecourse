<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'name','certificate','thumbnail','type',
        'status','price','description','level','mentor_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    public function mentors()
    {
        return $this->belongsTo('App\Mentor', 'id');
    }

    public function chapters()
    {
        return $this->hasMany('App\Chapter')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\ImageCourse')->orderBy('id', 'DESC');
    }
}
