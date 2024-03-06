<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('military_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('rank_id')
                    ->relationship(
                        name: 'rank',
                        titleAttribute: 'name'
                    )
                    ->required()
                    ->preload()
                    ->native(false),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255),
                Forms\Components\Select::make('department_id')
                    ->relationship(
                        name: 'department',
                        titleAttribute: 'name',
                    )
                    ->required()
                    ->preload()
                    ->native(false)
                    ->hidden(auth()->user()->id != 1),
                Forms\Components\Select::make('roles')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('name', '<>', 'Admin'),
                    )
                    ->preload()
                    ->native(false)
                    ->placeholder('no role')
                    ->label('Role')
                    ->hidden(!auth()->user()->hasRole('Admin')),
                ]);
            
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if($data['password']){
            $record->password = bcrypt($data['password']);
        }
        $record->save();
    
        return $record;
    }
}
