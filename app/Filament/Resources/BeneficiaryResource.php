<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeneficiaryResource\Pages;
use App\Models\Beneficiary;
use App\Models\Sector;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laragear\Rut\Rut;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class BeneficiaryResource extends Resource
{
    protected static ?string $modelLabel = 'beneficiario';

    protected static ?string $model = Beneficiary::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre completo')
                    ->required(),
                Forms\Components\TextInput::make('rut')
                    ->label('RUT')
                    ->rules(['rut'])
                    ->rules(
                        ['rut_unique:beneficiaries,rut_num,rut_vd'],
                        fn (string $context): bool => $context === 'create'
                    )
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => strlen($state) > 3
                        ? $set('rut', Rut::parse($state)->format())
                        : $state
                    )
                    ->formatStateUsing(fn (?string $state) => $state ?? '')
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->validationAttribute('rut')
                    ->required(),

                PhoneInput::make('phone')
                    ->label('Teléfono')
                    ->defaultCountry('CL')
                    ->initialCountry('CL')
                    ->disallowDropdown()
                    ->inputNumberFormat(PhoneInputNumberType::E164)
                    ->separateDialCode(),
                Forms\Components\TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email(),

                Forms\Components\Select::make('sector_id')
                    ->label('Sector de residencia')
                    ->relationship('sector', 'name')
                    ->options(
                        Sector::all()
                            ->groupBy('type')
                            ->map(fn ($group) => $group->mapWithKeys(fn ($item) => [$item['id'] => $item['name']]))
                            ->mapWithKeys(fn ($item, $key) => [ucfirst($key) => $item])
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, $record, Set $set) {
                        // Si salgo de "Fuera de la comuna", resetear el campo city
                        if ($state && Sector::find($state)->name !== 'Fuera de la comuna') {
                            $set('city', '');
                            // Beneficiary::find($record->id)->update(['city' => '']);
                        }
                    }),

                Forms\Components\TextInput::make('city')
                    ->label('Ciudad')
                    ->visible(function (callable $get): bool {
                        $sector = $get('sector_id') ? Sector::find($get('sector_id'))->name : false;

                        return $sector === 'Fuera de la comuna';
                    })
                //                    ->disabled(function (callable $get): bool {
                //                        // Obtener el valor de la selección del sector
                //                        $sectorId = $get('sector_id');
                //
                //                        // Verificar si no se ha seleccionado nada
                //                        if (!$sectorId) {
                //                            return true; // Bloquea el campo si no hay selección
                //                        }
                //
                //                        // Cargar el sector y comprobar el nombre
                //                        $sector = Sector::find($sectorId);
                //
                //                        return $sector ? $sector->name !== 'Fuera de la comuna' : true;
                //                    })
                ,

                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rut')
                    ->label('RUT')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector.name')
                    ->label('Nombre del sector')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creacion')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de ingreso')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Fecha de borrado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('Sector')
                    ->form([
                        Forms\Components\Select::make('sector_id')
                            ->label('Sector')
                            ->helperText('Selecciona el sector correspondiente')
                            ->options(
                                Sector::all()
                                    ->groupBy('type')
                                    ->map(fn ($group) => $group->mapWithKeys(fn ($item) => [$item['id'] => $item['name']]))
                                    ->mapWithKeys(fn ($item, $key) => [ucfirst($key) => $item])
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['sector_id'] ?? null, function ($query, $sectorId) {
                            // Filtrar beneficiarios a través de la relación sector
                            $query->where('sector_id', $sectorId);
                        });
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Section::make('Datos del beneficiario')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('contact')
                            ->label('Contacto')
                            ->bulleted()
                            ->html()
                            ->state(fn ($record) => [
                                "<a class='underline' href='tel:{$record->phone}'>{$record->phone}</a>",
                                "<a class='underline' href='mailto:{$record->email}'>{$record->email}</a>",
                            ]),
                        TextEntry::make('residency')
                            ->label('Residencia')
                            ->listWithLineBreaks()
                            ->state(fn ($record) => [
                                $record->sector->name,
                                $record->city,
                            ]),
                    ]),

                Section::make('Anotaciones')
                    ->columnSpan(1)
                    ->collapsible()
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Notas')
                            ->state($record->notes ?? 'Sin anotaciones registradas.'),
                    ]),

                RepeatableEntry::make('hearings')
                    ->label('Audiencias')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('requested_at')
                            ->label('Audiencia solicitada el')
                            ->date('d-m-Y'),

                        TextEntry::make('hearing_date')
                            ->label('Fecha de la audiencia')
                            ->formatStateUsing(function ($record) {
                                $date = Carbon::parse($record->hearing_date)->format('d-m-Y');
                                $time = Carbon::parse($record->hearing_time)->format('H:i');

                                return "{$date} {$time}";
                            }),

                        TextEntry::make('requestType.name')
                            ->label('Tipo de Solicitud')
                            ->badge()
                            ->color(fn ($record) => $record->requestType->color
                                ? Color::rgb("{$record->requestType->color['type']}({$record->requestType->color['value']})")
                                : 'primary'
                            ),

                        // Campo que muestra "Rechazada" solo si el estado es "rejected"
                        TextEntry::make('state')
                            ->label('AUDIENCIA RECHAZADA')
                            ->color('danger')
                            ->badge()
                            ->hidden(fn ($record) => $record->status !== 'rechazado') // Muestra solo si es rechazado
                            ->formatStateUsing(fn ($record) => 'Rechazada'),
                        // Campo para asistencia (mostrar solo si no está rechazada)
                        IconEntry::make('did_assist')
                            ->label('Asistencia')
                            ->boolean()
                            ->hidden(fn ($record) => $record->status === 'rechazado'), // Oculta si está rechazada

                        Fieldset::make('Detalles de la solicitud')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('details')
                                    ->label('')
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Anotaciones de la audiencia')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('notes')
                                    ->label('')
                                    ->columnSpanFull(),
                            ]),
                        Fieldset::make('Adjuntos')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('attachment')
                                    ->label('')
                                    ->formatStateUsing(function ($state) {

                                        // Decodificar el JSON si es necesario
                                        $files = is_array($state) ? $state : json_decode($state, true);

                                        return collect($files)
                                            ->map(fn ($file) => "<a href='".asset('storage/'.$file)."' target='_blank'>Ver archivo</a>")
                                            ->implode('<br>'); // Genera una lista con saltos de línea
                                    })
                                    ->html()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBeneficiaries::route('/'),
            'view' => Pages\ViewBeneficiary::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getHeaderWidgets(): array
    {
        return [
            BeneficiaryResource\Widgets\BeneficiaryHearingStats::class, // El widget será mostrado aquí
        ];
    }
}
