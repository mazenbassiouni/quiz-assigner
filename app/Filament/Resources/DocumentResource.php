<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->required()
                    ->directory('documents')
                    ->columnSpan(2)
                    ->validationMessages([
                        'required' => 'File is required.',
                    ])
                    ->live(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\select::make('type')
                    ->required()
                    ->options([
                        'general' => 'General',
                        'specialized' => 'Specialized'
                    ])->native(false)->live(),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required(fn (Get $get): bool => $get('type') === 'specialized')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'specialized' || !auth()->user()->hasRole('Admin'))
                    ->disabled(fn (Get $get): bool => $get('type') !== 'specialized')
                    ->preload()->native(false),
                Forms\Components\Select::make('branch')
                    ->options([
                        'General' => 'General',
                        'Tech Docs' => 'Tech Docs',
                        'Maintenance' => 'Maintenance',
                        'OEM Courses' => 'OEM Courses'
                    ])
                    ->required(fn (Get $get): bool => $get('type') === 'specialized')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'specialized')
                    ->disabled(fn (Get $get): bool => $get('type') !== 'specialized')
                    ->preload()->native(false),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query){
                if( !auth()->user()->hasRole('Admin')){
                    $query->where('department_id', auth()->user()->department_id);
                }
            });
    }
}
