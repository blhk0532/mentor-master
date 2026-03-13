<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Shreejan\ActionableColumn\Tables\Columns\ActionableColumn;

class PostnummersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('post_nummer')
                    ->label('Post Nr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('post_ort')
                    ->label('Post Ort')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('post_lan')
                    ->label('Post Län')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ColumnGroup::make('Hitta', [
                    TextColumn::make('hitta_personer_total')
                        ->label('🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),
                    TextColumn::make('hitta_foretag_total')
                        ->label('🏛️🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('hitta_personer_saved')
                        ->label('💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('hitta_foretag_saved')
                        ->label('🏛️💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('hitta_personer_phone_saved')
                        ->label('☎️')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('hitta_personer_house_saved')
                        ->label('🏠')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),
                ]),

                ColumnGroup::make('Ratsit', [
                    TextColumn::make('ratsit_personer_total')
                        ->label('🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('ratsit_foretag_total')
                        ->label('🏛️🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('ratsit_personer_saved')
                        ->label('💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('ratsit_foretag_saved')
                        ->label('🏛️💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('ratsit_personer_phone_saved')
                        ->label('☎️')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('ratsit_personer_house_saved')
                        ->label('🏠')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),
                ]),

                ColumnGroup::make('Merinfo', [
                    TextColumn::make('merinfo_personer_total')
                        ->label('🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—'),

                    TextColumn::make('merinfo_foretag_total')
                        ->label('🏛️🌐')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('merinfo_personer_phone_total')
                        ->label('☎️')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: false),

                    TextColumn::make('merinfo_personer_saved')
                        ->label('💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('merinfo_foretag_saved')
                        ->label('🏛️💾')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('merinfo_personer_phone_saved')
                        ->label('☎️')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('merinfo_personer_house_saved')
                        ->label('🏠')
                        ->numeric()
                        ->sortable()
                        ->toggleable()
                        ->placeholder('—')
                        ->toggleable(isToggledHiddenByDefault: true),

                    IconColumn::make('merinfo_personer_queue')
                        ->label('📅')
                        ->boolean()
                        ->toggleable(),
                ]),

                IconColumn::make('is_active')
                    ->label('⩇⩇:⩇⩇')
                    ->boolean()
                    ->toggleable(),

                ActionableColumn::make('status')
                    ->badge()                                    // Display as badge (or remove for simple text)
                    ->color('success')                           // Badge/text color: success, danger, warning, info, primary
                    ->actionIcon(Heroicon::PencilSquare)         // Action button icon (Heroicon enum or string)
                    ->actionIconColor('warning')                 // Icon color (independent from badge color)
                    ->clickableColumn()                          // Make entire column clickable (or remove for button-only)
                    ->tapAction(
                        Action::make('changeStatus')              // Any Filament Action: edit, delete, approve, etc.
                            ->label('Change Status')
                            ->tooltip('Click to change status')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required(),
                            ])
                            ->fillForm(fn ($record) => [
                                'status' => $record->status,
                            ])
                            ->action(function ($record, array $data) {
                                $record->update($data);
                            })
                    ),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([10, 25, 50, 100, 200, 500, 1000])
            ->defaultPaginationPageOption(25)
            ->defaultSort('post_ort', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'complete' => 'Complete',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active')->default(true),

                TernaryFilter::make('has_phone')
                    ->label('Show Phone Only')
                    ->default(true)
                    ->queries(
                        true: fn ($query) => $query->where(function ($q) {
                            $q->where('hitta_personer_phone_saved', '>', 0)
                                ->orWhere('ratsit_personer_phone_saved', '>', 0)
                                ->orWhere('merinfo_personer_phone_saved', '>', 0);
                        }),
                        false: fn ($query) => $query->where(function ($q) {
                            $q->where('hitta_personer_phone_saved', '=', 0)
                                ->where('ratsit_personer_phone_saved', '=', 0)
                                ->where('merinfo_personer_phone_saved', '=', 0);
                        }),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->persistFiltersInSession()
            ->columnToggleFormColumns(3)
            ->columnToggleFormWidth('4xl');
    }
}
