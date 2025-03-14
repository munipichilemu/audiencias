<?php

namespace App\Filament\Pages\Widgets;

use App\Exports\BeneficiaryExport;
use App\Filament\Resources\BeneficiaryResource;
use App\Models\Hearing;
use App\Models\RequestType;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

// Importación de las clases de Filament Forms
// Importación específica de Wizard
// Importación específica de Step

class CalendarWidget extends FullCalendarWidget
{
    // Define el modelo que se usará para las acciones
    public string|\Illuminate\Database\Eloquent\Model|null $model = Hearing::class;

    public function fetchEvents(array $fetchInfo): array
    {
        $start = Carbon::parse($fetchInfo['start'])->setTimezone(config('app.timezone'));
        $end = Carbon::parse($fetchInfo['end'])->setTimezone(config('app.timezone'));

        return Hearing::query()
            ->whereDate('hearing_date', '>=', $start->startOfDay())
            ->whereDate('hearing_date', '<=', $end->endOfDay())
            ->with('beneficiary')
            ->get()
            ->map(function (Hearing $hearing) {
                // Determinar color según asistencia
                $color = match ($hearing->did_assist) {
                    1 => '#10B981',    // Verde si asistió
                    0 => '#EF4444',    // Rojo si no asistió
                    default => '#6B7280' // Gris si no hay registro
                };

                return [
                    'id' => $hearing->id,
                    'title' => $hearing->beneficiary->name,
                    'start' => Carbon::parse($hearing->hearing_date)
                        ->setTime(...explode(':', $hearing->hearing_time))
                        ->setTimezone(config('app.timezone')),
                    'end' => Carbon::parse($hearing->hearing_date)
                        ->setTime(...explode(':', $hearing->hearing_time))
                        ->addMinutes(15)
                        ->setTimezone(config('app.timezone')),
                    'color' => $color, // Propiedad para color del evento
                    'textColor' => '#FFFFFF', // Color texto blanco
                    'borderColor' => $color, // Borde del mismo color
                    'shouldOpenUrlInNewTab' => true,
                ];
            })
            ->toArray();
    }

    // Configuración del calendario
    public function config(): array
    {
        return [
            'initialView' => 'timeGridWeek',
            'firstDay' => 1, // Lunes como primer día de la semana
            'weekends' => false, // ocultar fines de semana
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'timeGridWeek,dayGridMonth',
            ],
            'slotMinTime' => '09:00:00', // Hora de inicio del calendario
            'slotMaxTime' => '17:15:00', // Hora de fin del calendario
            'slotDuration' => '00:15:00', // Intervalos de 15 minutos
            'slotLabelInterval' => '00:15:00', // Etiquetas cada 15 minutos
            'slotLabelFormat' => [
                'hour' => 'numeric',
                'minute' => 'numeric',
                'omitZeroMinute' => false,
            ],
            'eventTimeFormat' => [
                'hour' => 'numeric',
                'minute' => 'numeric',
                'omitZeroMinute' => false,
            ],
            'businessHours' => [
                [
                    'daysOfWeek' => [1, 2, 3, 4, 5], // Lunes a Viernes
                    'startTime' => '09:00',
                    'endTime' => '12:45',
                ],
                [
                    'daysOfWeek' => [1, 2, 3, 4, 5], // Lunes a Viernes
                    'startTime' => '14:15',
                    'endTime' => '17:00',
                ],
            ],
        ];
    }

    // Formulario de creación/edición
    public function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Antecedentes')
                        ->columns(2)
                        ->schema([
                            Select::make('beneficiary_id')
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

                            Select::make('request_type_id')
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

                            Textarea::make('details')
                                ->label('Detalles')
                                ->rows(6)
                                ->columnSpanFull(),

                            FileUpload::make('attachment')
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
                        ]),

                    Tabs\Tab::make('Audiencia') // Usar la clase Step importada
                        ->columns(2)
                        ->schema([
                            DatePicker::make('hearing_date')
                                ->label('Fecha audiencia')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($set) {
                                    $set('hearing_time', null);
                                }),

                            Select::make('hearing_time')
                                ->label('Hora de la audiencia')
                                ->required()
                                ->options(function ($get) {
                                    // Obtén las horas ocupadas para la fecha seleccionada
                                    $occupiedTimes = Hearing::query()
                                        ->whereDate('hearing_date', $get('hearing_date'))
                                        ->pluck('hearing_time')
                                        ->toArray();

                                    // Genera las horas disponibles
                                    return self::getAvailableTimes($occupiedTimes);
                                })
                                ->reactive(), // Habilita la reactividad

                            Textarea::make('notes')
                                ->label('Observaciones previas')
                                ->columnSpanFull(),
                        ]),
                ]),

        ];
    }

    // Obtener las horas disponibles
    protected static function getAvailableTimes(array $occupiedTimes): array
    {
        // Lógica para generar las horas disponibles
        $availableTimes = [];
        $startTime = Carbon::createFromTime(9, 0); // Hora de inicio
        $endTime = Carbon::createFromTime(17, 0); // Hora de fin

        while ($startTime->lt($endTime)) {
            $time = $startTime->format('H:i');
            if (! in_array($time, $occupiedTimes)) {
                $availableTimes[$time] = $time;
            }
            $startTime->addMinutes(15); // Intervalos de 15 minutos
        }

        return $availableTimes;
    }

    protected function viewAction(): Action
    {
        return parent::viewAction()
            ->modalHeading('Detalles de la audiencia')
            ->modalFooterActions($this->modalActions())
            ->modalCancelAction(false)
            ->infolist([
                \Filament\Infolists\Components\Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Antecedentes de la audiencia')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('beneficiary.name')
                                    ->label('Solicitante'),
                                TextEntry::make('requestType.name')
                                    ->label('Tipo de solicitud')
                                    ->badge(),

                                TextEntry::make('details')
                                    ->label('Detalles')
                                    ->lineClamp(6)
                                    ->state(fn (Hearing $record) => $record->details ?? 'No hay detalles disponibles.')
                                    ->columnSpanFull(),
                                TextEntry::make('notes')
                                    ->label('Notas')
                                    ->lineClamp(6)
                                    ->default(fn (Hearing $record) => $record->notes ?? 'No  hay notas disponibles. ')
                                    ->columnSpanFull(),

                                TextEntry::make('attachment')
                                    ->label('Archivo adjunto')
                                    ->visible(fn ($record) => $record->attachment)
                                    ->url(fn ($record) => asset("storage/{$record->attachment}"))
                                    ->openUrlInNewTab()
                                    ->state('Descargar archivos adjuntos'),
                            ]),

                        Tab::make('Ficha del solicitante')
                            ->schema([
                                TextEntry::make('beneficiary.name')
                                    ->label('Solicitante'),
                                TextEntry::make('beneficiary.rut')
                                    ->label('Rut'),
                                TextEntry::make('beneficiary.total_requests')
                                    ->label('Solicitudes de este año')
                                    ->state(function ($record) {
                                        return Hearing::where('beneficiary_id', $record->beneficiary->id)
                                            ->whereYear('created_at', now()->year)
                                            ->count();
                                    }),

                            ]),
                    ]),

            ]);
    }

    protected function headerActions(): array
    {
        return [];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->label('Notas')
                ->form([
                    TextArea::make('notes')
                        ->label('Observaciones')
                        ->required(),
                ]),
            DeleteAction::make()
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
            Action::make('attendance')
                ->label(fn (Hearing $record) => $record->did_assist ? 'Marcar ausente' : 'Marcar presente')
                ->color(fn (Hearing $record) => $record->did_assist ? 'danger' : 'success')
                ->icon(fn (Hearing $record) => $record->did_assist ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->extraAttributes(['style' => 'margin-left: auto;'])
                ->action(function (Hearing $record) {
                    $save = $record->update(['did_assist' => ! $record->did_assist]);
                    if ($save) {
                        $this->js('window.location.reload()');
                    }
                }),
            Action::make('download_excel')
                ->label('Reporte')
                ->icon('heroicon-o-table-cells')
                ->color(Color::Emerald)
                ->action(function (Hearing $record) {
                    $fileName = 'beneficiary_'.$record->beneficiary_id.'.xlsx';
                    Excel::store(new BeneficiaryExport($record->beneficiary_id), $fileName, 'public');

                    return redirect(Storage::url($fileName));
                }),
        ];
    }
}
