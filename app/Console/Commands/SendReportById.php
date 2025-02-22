<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employe;
use App\Models\ReportLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;

class SendReportById extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send {id : ID del reporte}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar reporte de empleados basado en un ID específico de ReportLog';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        Log::info("Iniciando generación de reporte para ReportLog ID: $id");

        // Buscar el registro de ReportLog por ID
        $reportLog = ReportLog::findOrFail($id);

        // Obtener las fechas de inicio y fin del reporte
        $startDate = $reportLog->start_date_last_report;
        $endDate = $reportLog->end_date_last_report;

        Log::info("Generando reporte desde $startDate hasta $endDate para el ID de ReportLog: $id");

        // Obtener los empleados que tienen registros en el rango de fechas
        $employes = Employe::whereHas('data', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('data.created_at', [$startDate, $endDate]);
        })
        ->with(['data' => function ($query) use ($startDate, $endDate) {
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

        // Enviar correo con el enlace al reporte, pasando los datos del ReportLog específico
        try {
            Mail::to($recipientEmail)->send(new WeeklyReportMail($reportLog));
            Log::info('Correo con enlace al reporte enviado al correo: ' . $recipientEmail);
        } catch (\Exception $e) {
            Log::error('Error al enviar el correo: ' . $e->getMessage());
        }

        Log::info('Reporte enviado exitosamente para ReportLog ID: ' . $id);

        return 0;
    }
}
