<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Question;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Question'),
        ];
    }

    public function table(Table $table): Table
    {
        if( request()->path() == 'livewire/update' 
            && array_key_exists('activeTab', request()->request->all()['components'][0]['updates'])
            && request()->request->all()['components'][0]['updates']['activeTab'] == 'true/false'
            || request()->activeTab == 'true/false'
        ){
            // dd('hi');
            $answer_column = Tables\Columns\IconColumn::make('answer')
                ->boolean()
                ->trueIcon('heroicon-o-check')
                ->falseIcon('heroicon-o-x-mark');
        }else{
            $answer_column = Tables\Columns\TextColumn::make('answer');
        }

        if(auth()->user()->hasRole('Admin')){
            $filters = [
                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department'),
                Tables\Filters\SelectFilter::make('level')
                    ->options(fn (): array => Question::query()->groupBy('level')->pluck('level','level')->all()),
            ];
        }else{
            $filters = [
                Tables\Filters\SelectFilter::make('level')
                    ->options(fn (): array => Question::query()->groupBy('level')->pluck('level','level')->all()),
            ];
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Question')
                    ->searchable(),
                $answer_column,
                // Tables\Columns\TextColumn::make('type')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters($filters)
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\deleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getTabs(): array
    {
        return [
            'mcq' => Tab::make('MCQ')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('type', 'mcq');
                }),
            'true/false' => Tab::make('True/False')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('type', 'true/false');
                }),
        ];
    }
}
