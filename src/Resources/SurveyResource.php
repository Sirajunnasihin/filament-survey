<?php

namespace Tapp\FilamentSurvey\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Forms\Get;
use Filament\Tables\Table;
use MattDaneshvar\Survey\Models\Survey;
use Tapp\FilamentSurvey\Resources\QuestionResource\Pages as QuestionPages;
use Tapp\FilamentSurvey\Resources\SurveyResource\Pages;
use Tapp\FilamentSurvey\Resources\SurveyResource\Widgets\Questions;

class SurveyResource extends Resource
{
    use Translatable;

    protected static ?string $model = Survey::class;

    public static function getNavigationIcon(): string
    {
        return config('filament-survey.navigation.survey.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-survey.navigation.survey.sort');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-survey::filament-survey.navigation.group');
    }

    public static function getLabel(): string
    {
        return __('filament-survey::filament-survey.navigation.survey.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-survey::filament-survey.navigation.survey.plural-label');
    }

    public static function getTranslatableLocales(): array
    {
        return array_keys(config('filament-survey.languages'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Checkbox::make('limit')
                    ->label('Limit entries per participant?')
                    ->live()
                    ->inline(false),
                Forms\Components\TextInput::make('limit_per_participant')
                    ->default(1)
                    ->visible(function (Get $get) {
                        return $get('limit');
                    })
                    ->minValue(1)
                    ->numeric(),
                Forms\Components\Checkbox::make('allow_guests')
                    ->label('Allow guest users to respond to survey?')
                    ->inline(false),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name (English)')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('settings'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->actions([
                Action::make('CreateQuestion')
                    ->url(fn (Survey $record): string => route('filament.admin.resources.surveys.create-question', $record->id))
                    ->color('success'),
                DeleteAction::make(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'create-question' => QuestionPages\CreateQuestion::route('/{survey_id}/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Questions::class,
        ];
    }
}
