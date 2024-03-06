<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use App\Models\Question;
use App\Models\Answer;
use App\Models\CorrectAnswer;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Create question')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label('Question')
                            ->maxLength(255)
                            ->columnSpan(3),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'mcq' => 'MCQ',
                                'true/false' => 'True or False'
                            ])->native(false)
                            ->live()
                            ->default('mcq')
                            ->columnSpan(1),
                        Forms\Components\Select::make('level')
                            ->required()
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                            ])->native(false)
                            ->columnSpan(1),
                        Forms\Components\Select::make('department_id')
                            ->required()
                            ->relationship('department', 'name')
                            ->native(false)
                            ->hidden(!auth()->user()->hasRole('Admin'))
                            ->columnSpan(1),
                    ])->columns(3),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('is_right')
                            ->label('True or False')
                            ->required(fn (Get $get): bool => $get('type') === 'true/false')
                            ->options([
                                '1' => 'True',
                                '0' => 'False',
                            ])->hidden(fn (Get $get): bool => $get('type') !== 'true/false')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'true/false'),
                        Forms\Components\TextInput::make('answer_1')
                            ->required(fn (Get $get): bool => $get('type') === 'mcq')
                            ->label('Answer 1')
                            ->maxLength(255)
                            ->hidden(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'mcq'),
                        Forms\Components\TextInput::make('answer_2')
                            ->required(fn (Get $get): bool => $get('type') === 'mcq')
                            ->label('Answer 2')
                            ->maxLength(255)
                            ->hidden(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'mcq'),
                        Forms\Components\TextInput::make('answer_3')
                            ->required(fn (Get $get): bool => $get('type') === 'mcq')
                            ->label('Answer 3')
                            ->maxLength(255)
                            ->hidden(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'mcq'),
                        Forms\Components\TextInput::make('answer_4')
                            ->required(fn (Get $get): bool => $get('type') === 'mcq')
                            ->label('Answer 4')
                            ->maxLength(255)
                            ->hidden(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'mcq'),
                        Forms\Components\Select::make('correct_answer')
                            ->required(fn (Get $get): bool => $get('type') === 'mcq')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4', 
                            ])->native(false)
                            ->hidden(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->disabled(fn (Get $get): bool => $get('type') !== 'mcq')
                            ->columnSpan(2),
                    ])->columns(2),
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = new (static::getModel())();
        $record->fill($data);

        if(auth()->user()->hasRole('Department Head')){
            $record->department_id = auth()->user()->department_id;
        }

        $record->save();

        if($data['type'] == 'mcq'){
            for ($i=1; $i <= 4; $i++) {
                $answer = new Answer(['title' => $data['answer_'.$i]]);
                $record->answers()->save($answer);
                if($data['correct_answer'] == $i){
                    $correct_answer = new CorrectAnswer(['answer_id' => $answer->id]);
                    $record->correctAnswer()->save($correct_answer);
                }
            }
        }
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
