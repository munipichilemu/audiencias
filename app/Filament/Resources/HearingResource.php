<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HearingResource\Pages;
use App\Models\Hearing;
use App\Models\RequestType;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laragear\Rut\Rut;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class HearingResource extends Resource
{
    protected static ?string $modelLabel = 'audiencia';

    protected static ?string $model = Hearing::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\DatePicker::make('requested_at')
                    ->label('Fecha de creacion')
                    ->default(Carbon::now()->format('Y-m-d'))
                    ->required(),

                Forms\Components\Select::make('beneficiary_id')
                    ->label('Solicitante')
                    ->relationship('beneficiary', 'name')
                    ->searchable(['name', 'rut_num'])
                    ->preload()
                    ->createOptionForm(fn (Form $form): Form => BeneficiaryResource::form($form)
                        ->columns(2)
                    )
                    ->editOptionForm(fn (Form $form): Form => BeneficiaryResource::form($form)
                        ->columns(2)
                        ->operation('edit')
                    )
                    ->columnStart(1)
                    ->required(),

                Forms\Components\Select::make('request_type_id')
                    ->label('Tipo de Solicitud')
                    ->options(
                        RequestType::all()->mapWithKeys(fn ($type) => [
                            $type->id => (new HtmlString(
                                sprintf(
                                    "<span class='font-bold'>%s</span><span class='block text-xs text-gray-500 dark:text-gray-400'>%s</span>",
                                    $type->name,
                                    $type->description
                                )
                            ))->toHtml(),
                        ])->toArray()
                    )
                    ->allowHtml(true)
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('details')
                    ->label('Detalles')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('attachment')
                    ->label('Adjuntar archivo')
                    ->directory('attachments/hearings')
                    ->openable()
                    ->downloadable()
                    ->previewable()
                    ->columnSpanFull()
                    ->acceptedFileTypes([
                        'application/pdf',                                                              // .pdf
                        'application/msword',                                                           // .doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',      // .docx
                        'application/vnd.ms-excel',                                                     // .xls
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',            // .xlsx
                        'application/vnd.ms-powerpoint',                                                // .ppt
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',    // .pptx
                        'image/jpeg',                                                                   // .jpg
                        'image/png',                                                                    // .png
                        'image/tiff',                                                                   // .tif
                        'application/zip',                                                              // .zip
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('beneficiary.name')
                    ->label('Beneficiario')
                    ->description(fn ($record): string => Rut::parse($record->beneficiary->rut)->format())
                    ->searchable(['name', 'rut_num'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('requestType.name')
                    ->label('Solicitud')
                    ->badge()
                    ->color(fn ($record) => $record->requestType->color
                        ? Color::rgb("{$record->requestType->color['type']}({$record->requestType->color['value']})")
                        : 'primary'
                    )
                    ->description(fn (Hearing $record): string => Str::limit($record->details ?? '', 40, preserveWords: true))
                    ->sortable(),
                Tables\Columns\TextColumn::make('hearing_date')
                    ->label('Fecha de audiencia')
                    ->state(function (Hearing $record) {
                        // Si no tiene fecha y hora
                        if ($record->hearing_date === null && $record->hearing_time === null) {
                            return 'Sin agendar';
                        }

                        //  tiene ambos datos, formatear correctamente
                        return Carbon::parse($record->hearing_date)->format('d/m/Y');
                    })
                    ->description(fn (Hearing $record) => (
                        $record->hearing_date
                            ? Carbon::parse($record->hearing_time)->format('H:i') // Día de la semana
                            : 'Pendiente'
                    )),

                Tables\Columns\TextColumn::make('attachment')
                    ->label('Archivos Adjuntos')
                    ->formatStateUsing(function ($state) {
                        // Genera una lista de enlaces si hay múltiples archivos
                        $files = json_decode($state, true) ?? [];

                        return collect($files)
                            ->map(fn ($file) => sprintf(
                                "<a href='%s' target='_blank'>Ver archivo</a>",
                                asset("storage/{$file}")
                            ))
                            ->implode('<br>'); // Separa los enlaces con un salto de línea
                    })
                    ->html(true)
                    ->columnSpanFull()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('requested_at')
                    ->label('Fecha de solicitud')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                DateRangeFilter::make('requested_at')
                    ->label('Fecha de solicitud')
                    ->withIndicator()
                    ->useRangeLabels()
                    ->modifyQueryUsing(fn (Builder $query, ?Carbon $startDate, ?Carbon $endDate, $dateString) => $query->when(
                        ! empty($dateString),
                        fn (Builder $query, $date): Builder => $query->whereBetween(
                            'requested_at',
                            [
                                $startDate->subDay(),
                                $endDate,
                            ]
                        )
                    )
                    ),
                Filter::make('Tipo de Solicitud')
                    ->form([
                        Select::make('request_type_id')
                            ->label('Tipo de Solicitud') // Etiqueta visible ->description(fn($record): string => $record->hearing_time)
                            ->options(
                                RequestType::all()->mapWithKeys(fn ($type) => [
                                    $type->id => "<span style='font-weight: bold;'>{$type->name}</span> <span style='display: block; color: dimgray;'>{$type->description}</span>",
                                ])->toArray()
                            )
                            ->allowHtml(true)
                            ->searchable() // Habilitamos la búsqueda en el filtro
                            ->preload() // Precarga los datos para evitar múltiples consultas
                            ->nullable() // Permite no seleccionar ninguna opción
                            ->helperText('Seleccione un tipo de solicitud '),

                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['request_type_id'] ?? null, function ($query, $typeId) {
                            $query->where('request_type_id', $typeId);
                        });
                    }),
                Filter::make('Asistencia')
                    ->form([
                        Select::make('asistencia')
                            ->label('Estado de asistencia')
                            ->options([
                                'asistidos' => 'Asistidos', // Mostrar solo registros donde did_assist = 1
                                'no_asistidos' => 'No asistidos', // Mostrar solo registros donde did_assist = NULL
                                'todos' => 'Todos', // Mostrar todos los registros
                            ])
                            ->default('todos'), // Por defecto: "todos"
                    ])
                    ->query(function (Builder $query, array $data) {
                        // Validamos que la selección exista
                        if (isset($data['asistencia'])) {
                            switch ($data['asistencia']) {
                                case 'asistidos': // Mostrar asistidos
                                    $query->where('did_assist', 1);
                                    break;
                                case 'no_asistidos': // Mostrar no asistidos
                                    $query->whereNull('did_assist');
                                    break;
                                case 'todos': // Mostrar todos (sin condiciones)
                                    break;
                            }
                        }
                    }),
                Tables\Filters\TrashedFilter::make()
                    ->label('Estado de audiencia')
                    ->placeholder('Pendientes o aceptadas')
                    ->trueLabel('Todas las audiencias')
                    ->falseLabel('Solo rechazadas'),
                Filter::make('sin_agendar')
                    ->label('Audiencias Sin Agendar')
                    ->toggle()
                    ->default() // Opcional: activar por defecto
                    ->indicator('Audiencias pendientes de agendar')
                    ->query(function (Builder $query) {
                        $query->whereNull('hearing_date')
                            ->whereNull('hearing_time')
                            ->withoutTrashed(); // Excluir las eliminadas
                    }),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Rechazar')
                    ->successNotificationTitle('Audiencia rechazada')
                    ->modalHeading('Rechazar audiencia')
                    ->modalDescription('¿Desea rechazar la audiencia?')
                    ->action(function (Hearing $record) {
                        $record->update([
                            'hearing_date' => null,
                            'hearing_time' => null,
                        ]);
                        $record->delete();
                    }),
                Tables\Actions\RestoreAction::make()
                    ->label('Restaurar'),
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
            'index' => Pages\ManageHearings::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]); // Esto permite mostrar tanto registros activos como eliminados

    }

    public static function rules(): array
    {
        return [
            'hearing_date' => 'required|date',
            'hearing_time' => [
                'required',
                Rule::unique('hearings', 'hearing_time')
                    ->where('hearing_date', request('hearing_date'))
                    ->ignore(request('record')),
            ],
        ];
    }

    public static function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\HearingStats::class,
        ];
    }
}
