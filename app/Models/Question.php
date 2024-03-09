<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'level',
        'department_id',
        'is_right',
    ];

    protected $casts = [
        'is_right' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function correctAnswer()
    {
        return $this->hasOne(CorrectAnswer::class);
    }

    public function getAnswerTitleAttribute()
    {
        if($this->type == 'mcq'){
            $answer = $this->correctAnswer->answer->title;
        }else{
            $answer = $this->is_right;
        }

        return $answer;
    }

    public function getAnswerAttribute()
    {
        if($this->type == 'mcq'){
            $answer = $this->correctAnswer->answer->id;
        }else{
            $answer = $this->is_right;
        }

        return $answer;
    }

    
}
