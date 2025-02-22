<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employe;
use App\Models\ReportLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;

class GenerateWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar reporte semanal de empleados con sus registros e ítems';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Iniciando generación de reporte semanal...');

        // Calcular el rango de la semana actual
        $endDate = Carbon::now()->subDay()->setTime(23, 59, 59); // El jueves anterior hasta las 11:59:59 PM
        $startDate = Carbon::now()->subWeek()->previous(Carbon::FRIDAY)->setTime(23, 59, 59); // El viernes anterior a las 11:59:59 PM

        Log::info("Generando reporte desde $startDate hasta $endDate");

        // Obtener los empleados que tienen registros en la semana
        $employes = Employe::whereHas('data', function ($query) use ($startDate, $endDate) {
            // Solo incluir empleados que tienen datos dentro del rango de fechas
            $query->whereBetween('data.created_at', [$startDate, $endDate]);
        })
        ->with(['data' => function ($query) use ($startDate, $endDate) {
            // Traer los datos y los ítems relacionados para esos empleados
            $query->whereBetween('data.created_at', [$startDate, $endDate])
                  ->with('items')
                  ->select('employe_id', 'items_id', \DB::raw('SUM(qty) as total_qty'), \DB::raw('SUM(qty * items.price) as total_amount'))
                  ->join('items', 'items.id', '=', 'data.items_id')
                  ->groupBy('employe_id', 'items_id');
        }])->get();

        $generalTotal = 0;

        // Procesar los datos
        foreach ($employes as $employe) {
            Log::info("Empleado: " . $employe->name);

            $totalEmpleado = 0;
            foreach ($employe->data as $data) {
                Log::info('  Ítem: ' . $data->items->name . ' - Cantidad: ' . $data->total_qty . ' - Total: ' . $data->total_amount);
                $totalEmpleado += $data->total_amount;
            }

            Log::info('Total general para ' . $employe->name . ': ' . $totalEmpleado);
            $generalTotal += $totalEmpleado;
        }

        Log::info('Total general para todos los empleados: ' . $generalTotal);
        $recipientEmail = env('REPORT_EMAIL', 'inversiones702sa@gmail.com');

        // Guardar la fecha de este reporte
        $reportLog = ReportLog::create([
            'last_report_date' => Carbon::now(),
            'start_date_last_report' => $startDate,
            'end_date_last_report' => $endDate,
        ]);

        // Enviar correo con enlace al reporte, pasando los datos
        Mail::to($recipientEmail)->send(new WeeklyReportMail($reportLog));
        Log::info('Correo con enlace al reporte enviado al correo: ' . $recipientEmail);

        Log::info('Reporte semanal generado con éxito. Fecha de último reporte guardada.');

        return 0;
    }
}







