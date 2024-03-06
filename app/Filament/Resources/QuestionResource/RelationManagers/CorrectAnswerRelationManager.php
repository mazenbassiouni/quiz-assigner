<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CorrectAnswerRelationManager extends RelationManager
{
    protected static string $relationship = 'CorrectAnswer';

    protected static ?string $label = 'Correct Answer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('technologies')
                ->required()
                ->relationship(
                    name: 'answer',
                    titleAttribute: 'title',
                    modifyQueryUsing: fn (Builder $query) => $query->where('question_id', $this->getOwnerRecord()->id),

                )->native(false),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('answer.title')->label('Correct Answer'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->paginated(false)->heading(false);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'mcq';
    }
}
