<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Arr;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Crew';

    protected static ?string $pluralModelLabel = 'Crew';

    public static function form(Form $form): Form
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
                    ->unique()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('department_id')
                    ->relationship(
                        name: 'department',
                        titleAttribute: 'name',
                    )
                    ->preload()
                    ->native(false)
                    ->hidden(!auth()->user()->hasRole('Admin')),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('military_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rank.name'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->hidden(!auth()->user()->hasRole('Admin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->hidden(!auth()->user()->hasRole('Admin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make()->native(false),
                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\Action::make('assignQuiz')
                    ->form([
                        Forms\Components\TextInput::make('count')
                            ->numeric()
                            ->label('How many question?')
                            ->required(),
                        Forms\Components\Select::make('level')
                            ->label('Select level')
                            ->required()
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4', 
                                '5' => '5',
                            ])->native(false),
                        Forms\Components\TextInput::make('duration')
                            ->numeric()
                            ->label('Quiz duration in minutes')
                            ->required(),
                    ])
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->action(function (array $data, User $record) {
                        $record->assginQuiz($data['level'], $data['count'], $data['duration']);

                        Notification::make()
                            ->title('Quiz assigned successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query){
                $query->where('users.id', '<>', 1);
                if( !auth()->user()->hasRole('Admin')){
                    $query->where('department_id', auth()->user()->department_id)
                        ->where('users.id', '<>', auth()->user()->id);
                }
            })->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GeneralNotesRelationManager::class,
            RelationManagers\BehaviorNotesRelationManager::class,
        ];
    }
}
