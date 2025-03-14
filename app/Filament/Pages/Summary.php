<?php

namespace App\Filament\Pages;

use App\Exports\BeneficiaryExport;
use App\Filament\Pages\Widgets\CalendarWidget;
use App\Filament\Pages\Widgets\CountWidget;
use App\Models\Hearing;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laragear\Rut\Rut;
use Maatwebsite\Excel\Facades\Excel;

class Summary extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.summary';

    protected static ?string $title = 'Resumen';

    protected static ?string $slug = 'resumen';

    protected function getHeaderWidgets(): array
    {
        return [
            CountWidget::class,
            CalendarWidget::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Audiencias Pendientes Por Agendar')
            ->query(
                Hearing::query()
                    ->whereNull('hearing_date') // Solo audiencias sin fecha
                    ->whereNull('hearing_time') // Solo audiencias sin hora
                    ->orderby('requested_at', 'asc')
            )
            ->columns([
                TextColumn::make('requested_at')
                    ->label('Tiempo transcurrido')
                    ->formatStateUsing(function ($record) {
                        $solicitud = Carbon::parse($record->requested_at)->startOfDay();
                        $hoy = Carbon::now()->startOfDay();

                        return $solicitud->diffForHumans($hoy, [
                            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                            'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS,
                        ]);
                    })
                    ->description(fn ($record) => Carbon::parse($record->requested_at)
                        ->isoFormat('D [de] MMMM [de] YYYY'))
                    ->sortable()
                    ->tooltip('Tiempo desde la solicitud inicial'),
                TextColumn::make('beneficiary.name')
                    ->label('Beneficiario')
                    ->description(fn ($record): string => Rut::parse($record->beneficiary->rut)->format())
                    ->searchable(['name', 'rut_num'])
                    ->sortable(),

                TextColumn::make('requestType.name')
                    ->label('Solicitud')
                    ->badge()
                    ->color(fn ($record) => $record->requestType->color
                        ? Color::rgb("{$record->requestType->color['type']}({$record->requestType->color['value']})")
                        : 'primary'
                    )
                    ->description(fn (Hearing $record): string => Str::limit($record->details ?? '', 40, preserveWords: true))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(40)
                    ->columnSpanFull()
                    ->sortable(),
            ])
            ->actions([
                Action::make('annotate')
                    ->label('Notas')
                    ->icon('heroicon-o-pencil-square')
                    ->color(Color::Blue)
                    ->action(function (Hearing $record, array $data): void {
                        $record->update(['notes' => $data['note']]);
                    })
                    ->form([
                        Textarea::make('note')
                            ->label('Nueva Nota')
                            ->placeholder('Escribe las soluciones o pasos previos aquí...')
                            ->required(),
                    ])
                    ->modalHeading('Añadir Nota')
                    ->modalSubmitActionLabel('Guardar'),

                Action::make('schedule')
                    ->label('Agendar Hora')
                    ->icon('heroicon-o-clock')
                    ->color(Color::Orange)
                    ->action(function (Hearing $record, array $data): void {
                        $save = $record->update([
                            'hearing_date' => $data['hearing_date'],
                            'hearing_time' => $data['hearing_time'],
                        ]);
                        if ($save) {// Recargar la página después de agendar la hora
                            $this->js('window.location.reload()');
                        }

                    })
                    ->form([
                        DatePicker::make('hearing_date')
                            ->label('Fecha de Audiencia')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get) {
                                // Actualiza el estado de la fecha si es necesario
                                $set('hearing_date', $get('hearing_date'));
                            }),

                        Select::make('hearing_time')
                            ->label('Hora de Audiencia')
                            ->required()
                            ->options(function ($get) {
                                // Obtener las horas ocupadas para la fecha seleccionada
                                $occupiedTimes = Hearing::query()
                                    ->whereDate('hearing_date', $get('hearing_date'))
                                    ->pluck('hearing_time')
                                    ->toArray();

                                // Retornar las horas disponibles
                                return self::getAvailableTimes($occupiedTimes);
                            })
                            ->reactive(),
                    ])
                    ->modalHeading('Agendar Hora y Fecha')
                    ->modalSubmitActionLabel('Guardar'),

                ActionGroup::make([
                    Action::make('details')
                        ->label('Detalles audiencia')
                        ->icon('heroicon-o-eye')
                        ->color(Color::Slate)
                        ->infolist([
                            TextEntry::make('beneficiary.name')
                                ->label('Beneficiario'),

                            TextEntry::make('requestType.name')
                                ->label('Tipo de Solicitud')
                                ->badge(),

                            TextEntry::make('details')
                                ->label('Detalles')
                                ->state(fn (Hearing $record): string => $record->details ?? 'No hay detalles disponibles. ')
                                ->columnSpanFull()
                                ->lineClamp(6),

                            TextEntry::make('notes')
                                ->label('Notas')
                                ->default(fn (Hearing $record) => $record->notes ?? 'No  hay notas disponibles. ')
                                ->columnSpanFull(),
                        ])
                        ->modalHeading('Detalles de la Audiencia')
                        ->modalWidth('lg'),

                    Action::make('download')
                        ->label('Descargar adjuntos')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color(Color::Cyan)
                        ->hidden(fn ($record) => ! $record->attachment)
                        ->url(fn ($record) => asset("storage/{$record->attachment}"))
                        ->openUrlInNewTab(),

                    Action::make('download_excel')
                        ->label('Informe beneficiario')
                        ->icon('heroicon-o-table-cells')
                        ->color(Color::Emerald)
                        ->action(function (Hearing $record) {
                            $fileName = 'beneficiary_'.$record->beneficiary_id.'.xlsx';
                            Excel::store(new BeneficiaryExport($record->beneficiary_id), $fileName, 'public');

                            return redirect(Storage::url($fileName));
                        }),
                ]),
            ]);
    }

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
}
