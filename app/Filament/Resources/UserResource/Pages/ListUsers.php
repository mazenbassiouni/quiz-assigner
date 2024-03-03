<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
 
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Force'),
            'active' => Tab::make('Officers')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->join('ranks', 'users.rank_id', '=', 'ranks.id')
                    ->where('ranks.category_id', 1)
                    ->select('users.*');
                }),
            'inactive' => Tab::make('Sub-officers')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->join('ranks', 'users.rank_id', '=', 'ranks.id')
                    ->where('ranks.category_id', 2)
                    ->select('users.*');
                }),
        ];
    }
}
