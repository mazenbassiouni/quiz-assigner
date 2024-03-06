<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = new (static::getModel())();
        $record->fill($data);

        if(auth()->user()->hasRole('Department Head')){
            $record->department_id = auth()->user()->department_id;
        }

        if($data['password']){
            $record->password = bcrypt($data['password']);
        }

        $record->save();

        return $record;
    }
}
