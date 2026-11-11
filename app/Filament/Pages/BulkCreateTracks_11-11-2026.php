<?php

namespace App\Filament\Pages;

use App\Models\CatalogFolder;
use App\Models\CatalogLevel;
use App\Models\CatalogMusicStyle;
use App\Models\CatalogSection;
use App\Models\Track;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class BulkCreateTracks extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Массовое добавление';

    protected static ?string $title = 'Массовое добавление треков';

    protected static ?string $navigationGroup = 'Музыкальный каталог';

    protected static string $view = 'filament.pages.bulk-create-tracks';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tracks' => [
                [],
            ],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([

                Forms\Components\Section::make('Общие данные')
                    ->schema([

                        Forms\Components\Select::make('section_id')
                            ->label('Раздел')
                            ->options(
                                CatalogSection::query()
                                    ->where('is_active', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('folder_id')
                            ->label('Папка')
                            ->options(function (callable $get) {

                                if (!$get('section_id')) {
                                    return [];
                                }

                                return CatalogFolder::query()
                                    ->where('section_id', $get('section_id'))
                                    ->where('is_active', true)
                                    ->orderBy('folder_code')
                                    ->pluck('folder_code', 'id');
                            })
                            ->required(),

                        Forms\Components\Select::make('music_styles')
                            ->label('Стили музыки')
                            ->multiple()
                            ->options(
                                CatalogMusicStyle::query()
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->pluck('name', 'id')
                            )
                            ->required(),

                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price_mp3')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price_flac')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price_bundle')
                            ->numeric()
                            ->required(),

                    ])
                    ->columns(4),

                Forms\Components\Section::make('Треки')
                    ->schema([

                        Forms\Components\Repeater::make('tracks')
                            ->label('')
                            ->defaultItems(1)
                            ->schema([

                                Forms\Components\TextInput::make('article')
                                    ->label('Артикул')
                                    ->required(),

                                Forms\Components\TextInput::make('track_name')
                                    ->label('Служебное имя')
                                    ->required(),

                                Forms\Components\Select::make('levels')
                                    ->label('Разряды')
                                    ->multiple()
                                    ->searchable()
                                    ->live()
                                    ->options(function (callable $get) {

                                        $sectionId = $get('../../section_id');

                                        if (!$sectionId) {
                                            return [];
                                        }

        return CatalogLevel::query()
            ->where('section_id', $sectionId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('level_name', 'id')
            ->toArray();
    }),

                                Forms\Components\TextInput::make('duration')
                                    ->label('Длительность')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('preview_code')
                                    ->label('Preview Code')
                                    ->required(),

                            ])
                            ->columns(5)
                            ->addActionLabel('Добавить ещё трек'),

                    ]),

            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data['tracks'] as $trackData) {

            $track = Track::create([
                'article'      => $trackData['article'],
                'track_name'   => $trackData['track_name'],
                'section_id'   => $data['section_id'],
                'folder_id'    => $data['folder_id'],
                'duration'     => $trackData['duration'],
                'price_mp3'    => $data['price_mp3'],
                'price_flac'   => $data['price_flac'],
                'price_bundle' => $data['price_bundle'],
                'preview_code' => $trackData['preview_code'],
                'year'         => $data['year'],
                'is_active'    => true,
            ]);

            $track->levels()->sync(
                $trackData['levels'] ?? []
            );

            $track->musicStyles()->sync(
                $data['music_styles'] ?? []
            );
        }

        Notification::make()
            ->title('Треки успешно добавлены')
            ->success()
            ->send();

        $this->form->fill([
            'tracks' => [
                [],
            ],
        ]);
    }
}
