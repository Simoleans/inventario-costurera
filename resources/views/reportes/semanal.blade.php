<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Semanal</title>
    <style>
        @media print {
            @page {
                size: auto;
                margin: 20mm;
            }
            .page-break {
                page-break-before: always;
            }
            .page-number:after {
                content: "Página " counter(page);
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .header {
            margin-bottom: 0px;
        }

        .header img {
            max-width: 150px;
        }

        .title h4 {
            font-size: 16px;
            margin-bottom: 0px;
        }

        .table-wrapper {
            margin-bottom: 0px;
        }

        .page-number {
            position: fixed;
            bottom: 0;
            right: 0;
        }

        .span-total {
            font-size: 16px;
            font-weight: 600;
        }

        .text-total {
            font-size: 20px;
            font-weight: 600;
        }


    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="header">
        <img src="{{ asset('/icons/logo.png') }}" alt="Logo">
    </div>

    <!-- Mostrar rango de fechas -->
    <p>Este reporte es del día {{ $startDate }} al día {{ $endDate }}</p>

    <!-- Condicional para verificar si hay empleados -->
    @if($employes->isEmpty())
        <p><strong>Esta semana no hubo registro</strong></p>
    @else
        @foreach($employes as $employe)
            <div class="title">
                <h4>{{ $employe->name }}</h4> <!-- Nombre del empleado más pequeño (h4) -->
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Ítem</th>
                            <th>Cliente</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            {{-- <th>A PAGAR</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employe->data as $data)
                        <tr>
                            <td>{{ $data->items->name }}</td>
                            <td>{{ $data->client->name_complete ?? 'N/T' }}</td>
                            <td>{{ $data->total_qty }}</td>
                            <td>${{ $data->total_amount }}</td>
                            {{-- <td>{{ $data->a_pagar }}</td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <span class="span-total">Total para {{ $employe->name }}:</span> <span class="text-total"> ${{ $employe->data->sum('total_amount') }}</span><br> A PAGAR: {{ $employe->a_pagar_total }} <!-- Total del empleado con "A PAGAR" -->
            </div>
        @endforeach

        <p><span class="span-total">Total para todos los costureros:</span> <span class="text-total">${{ $general_total }}<span> <br> A PAGAR: {{ $general_total_a_pagar }}</p> <!-- Total general de todos los empleados con "A PAGAR" -->
    @endif

    <!-- Número de página -->
    {{-- <div class="page-number"></div> --}}
</body>
</html>
