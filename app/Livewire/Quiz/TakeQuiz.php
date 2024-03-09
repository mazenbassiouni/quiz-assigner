<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Models\Quiz;

class TakeQuiz extends Component
{
    public int $id;

    public $answers;

    public function mount(int $id){
        $this->id = $id;
    }

    public function save($form){
        $quiz = Quiz::findOrFail($this->id);
        if($quiz->grade === null){
            $quiz->answers = $form;
            $quiz->gradeQuiz();
        }
        return $this->redirect('/assignments', navigate: true);
    }

    public function render()
    {
        $quiz = Quiz::findOrFail($this->id);
        $quiz->user_id != auth()->user()->id ? $this->redirectRoute('assignments') : '';
        $quiz->is_opened = true;
        $quiz->save();

        return view('livewire.pages.quiz.take-quiz')->with([
            'quiz' => $quiz,
        ]);
    }
}
