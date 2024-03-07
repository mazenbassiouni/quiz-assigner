<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        if( request()->path() == 'livewire/update' 
            && array_key_exists('activeTab', request()->request->all()['components'][0]['updates'])
            && request()->request->all()['components'][0]['updates']['activeTab'] == 'specialized'
            || request()->activeTab == 'specialized' || str_contains(request()->headers->get('referer'), 'activeTab=specialized')
        ){
            $custom_columns = [
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch')
                    ->searchable(),
            ];

            if(auth()->user()->hasRole('Admin')){
                $custom_filters = [
                    Tables\Filters\SelectFilter::make('department_id')
                        ->relationship('department', 'name')
                        ->label('Department')
                        ->native(false),
                    Tables\Filters\SelectFilter::make('branch')
                        ->options(fn (): array => Document::where('type','specialized')->groupBy('branch')->pluck('branch','branch')->all())
                        ->native(false),
                ];
            }else{
                $custom_filters = [
                    Tables\Filters\SelectFilter::make('branch')
                        ->options(fn (): array => Document::where('type','specialized')->groupBy('branch')->pluck('branch','branch')->all())
                        ->native(false),
                ];
            }
        }else{
            $custom_columns = [];
            $custom_filters = [];

        }



        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                ...$custom_columns,
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                ...$custom_filters
            ])
            ->actions([
                Tables\Actions\Action::make('sendEmail')
                    ->icon('heroicon-s-folder-arrow-down')
                    ->iconButton()
                    ->action(function (Model $record) {
                        return Storage::download('public/'.$record->path, $record->name);
                    }),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
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
            'general' => Tab::make('General')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('type', 'general');
                }),
            'specialized' => Tab::make('Specialized')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('type', 'specialized');
                }),
        ];
    }
}
