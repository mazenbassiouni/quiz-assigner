<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
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
                    ->disabled()
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
                Forms\Components\Radio::make('is_right')
                    ->label('True or False')
                    ->required(fn (Get $get): bool => $get('type') == 'true/false')
                    ->options([
                        '1' => 'True',
                        '0' => 'False',
                    ])->hidden(fn (Get $get): bool => $get('type') == 'mcq')
                    ->disabled(fn (Get $get): bool => $get('type') == 'mcq'),
            ])->columns(3),
        ]);
    }
}
