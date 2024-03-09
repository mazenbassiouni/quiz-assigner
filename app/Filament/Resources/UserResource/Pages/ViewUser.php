<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Forms;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Actions\Action;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add Note')
                ->form([
                    Forms\Components\Textarea::make('description')->required(),
                ])->action(function (array $data, User $record): void {
                    $record->generalNotes()->create($data);

                    Notification::make()
                        ->title('Note added successfully')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('addBehavior')
                ->form([
                    Forms\Components\Textarea::make('description')->required(),
                    Forms\Components\Radio::make('is_positive')->label('Type')
                        ->options([
                            '1' => 'Positive',
                            '0' => 'Negative',
                        ])->required(),
                ])->action(function (array $data, User $record): void {
                    $record->behaviorNotes()->create($data);

                    Notification::make()
                        ->title('Behavior note added successfully')
                        ->success()
                        ->send();
                }),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()->schema([
                    TextEntry::make('military_number'),
                    TextEntry::make('rank.name'),
                    TextEntry::make('name'),
                    TextEntry::make('department.name'),
                    TextEntry::make('roles.name')
                        ->label('Role')
                        ->default('No role')
                        ->hidden(!auth()->user()->hasRole('Admin')),
                ])->columns(3),
                Tabs::make()->schema([
                    Tabs\Tab::make('generalNotes')->schema([
                        TextEntry::make('generalNotes.description')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->label(false)
                    ])->label('General notes')
                    ->icon('heroicon-o-document')
                    ->iconPosition(IconPosition::Before),

                    Tabs\Tab::make('positiveBehavior')->schema([
                        TextEntry::make('positiveBehavior.description')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->label(false)
                    ])->label('Positive behavior')
                    ->icon('heroicon-o-document-plus')
                    ->iconPosition(IconPosition::Before),
                    
                    Tabs\Tab::make('negativeBehavior')->schema([
                        TextEntry::make('negativeBehavior.description')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->label(false)
                    ])->label('Negative behavior')
                    ->icon('heroicon-o-document-minus')
                    ->iconPosition(IconPosition::Before),

                    Tabs\Tab::make('quizzesHistory')->schema([
                        RepeatableEntry::make('quizzes')
                            ->schema([
                                TextEntry::make('grade')
                                    ->badge()
                                    ->formatStateUsing(fn ($state): string => $state.'%'),
                                TextEntry::make('level'),
                                    
                                TextEntry::make('duration')
                                    ->formatStateUsing(fn ($state): string => $state.'min'),
                                TextEntry::make('questions_count')->label('Questions'),
                                TextEntry::make('submited_at')
                                    ->dateTime()
                                    ->formatStateUsing(fn ($state): string => $state->format('d/m/Y'))
                                    ->label('Submited at'),
                                IconEntry::make('is_opened')
                                    ->label('Seen')
                                    ->icon(fn ($state): string => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash'),
                                // TextEntry::make('grade')
                                //     ->label('View Quiz')
                                //     ->formatStateUsing(fn ($state): string => '->')
                                //     ->suffixAction(
                                //         Action::make('viewQuiz')
                                //             ->icon('heroicon-m-eye')
                                //             ->action(function ($record) {
                                //                 return Notification::make()
                                //                     ->title('xxxx')
                                //                     ->success()
                                //                     ->send();
                                //             })
                                //     )
                            ])
                            ->columns(4)
                    ])
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconPosition(IconPosition::Before),

                ])
            ])->columns(1);
    }

    public function getRelationManagers(): array
    {
        return [];
    }

}
