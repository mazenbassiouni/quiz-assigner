<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'questions' => 'json',
        'answers' => 'json',
        'is_opened' => 'boolean',
    ];

    public function gradeQuiz(){
        if($this->answers){
            $questions = 0;
            $correct = 0;
            foreach($this->questions as $qst){
                $qst = Question::find($qst);
                if($qst){
                    $questions++;
                    if(key_exists($qst->id, $this->answers)){
                        $this->answers[$qst->id] == $qst->answer ? $correct++ : '';
                    }
                }
            }
            $this->grade = number_format( 100/$questions*$correct ,2);
            $this->save();
        }else{
            $this->answers = [];
            $this->grade = 0;
            $this->save();
        }
    }
}
