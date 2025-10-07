<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReport implements FromView, WithColumnWidths, WithStyles, WithEvents
{
    use Exportable;

    /**
     * Rango de fechas, datos y flag para vista detallada
     */
    protected $fecha1;
    protected $fecha2;
    protected $marcajes;
    protected $detallado; // <- flag que decide la vista

    /**
     * @param  mixed  $date1
     * @param  mixed  $date2
     * @param  mixed  $marcajes
     * @param  bool   $detallado
     */
    public function __construct($date1 = "", $date2 = "", $marcajes = [], bool $detallado = false)
    {
        $this->fecha1    = $date1;
        $this->fecha2    = $date2;
        $this->marcajes  = $marcajes;
        $this->detallado = $detallado;
    }

    /**
     * Anchos de columnas (ajusta si tu vista detallada tiene más columnas).
     */
    public function columnWidths(): array
    {
        // Si la vista detallada tiene más columnas, puedes ajustar aquí
        // Por ejemplo, para 12 columnas A-L:
        // return ['A'=>18,'B'=>18,'C'=>14,'D'=>24,'E'=>16,'F'=>18,'G'=>32,'H'=>32,'I'=>14,'J'=>14,'K'=>14,'L'=>12];
        if ($this->detallado) {
            return ['A'=>16,'B'=>16,'C'=>14,'D'=>20,'E'=>16,'F'=>16,'G'=>32,'H'=>32,'I'=>14,'J'=>14,'K'=>14,'L'=>10];
        }else {
            return [
                'A' => 50, 'B' => 50, 'C' => 50, 'D' => 20, 'E' => 30, 'F' => 15, 'G' => 15
            ];
        }
    }

    /**
     * Estilos base
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            // Encabezados grandes (ajusta rango si tu vista detallada usa más columnas)
            'A1:G1'  => ['font' => ['size' => 22]],
            'A2:G2'  => ['font' => ['size' => 14]],
            'A3:G3'  => ['font' => ['size' => 14]],
            'A4:G4'  => ['font' => ['size' => 13]],
        ];
    }

    /**
     * Eventos de la hoja (Before/After)
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Orientación horizontal
                $event->sheet->getDelegate()->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Detecta el rango usado en la hoja
                $highestRow = $sheet->getHighestRow();

                // Recorremos todas las filas buscando encabezados y pies
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("A{$row}")->getValue();

                    // Cabecera (thead) que empieza con "Fecha"
                    if ($cellValue === 'Fecha') {
                        // Ajusta el rango de columnas según tu vista
                        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'D9D9D9'],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }

                    // Pie de tabla (detectamos palabras clave)
                    if (is_string($cellValue) && in_array($cellValue, ['Minutos Art. 11', 'Minutos Tarde', 'Gran Total'])) {
                        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                        ]);
                    }

                    // "Total marcajes de ..."
                    if (is_string($cellValue) && function_exists('str_starts_with') && str_starts_with($cellValue, 'Total marcajes de')) {
                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                        ]);
                    }
                }

                // Inserta filas y da estilo a bloques que contienen "DNI"
                for ($row = $highestRow; $row >= 1; $row--) {
                    $cellValue = $sheet->getCell("A{$row}")->getValue();

                    if (is_string($cellValue) && strpos($cellValue, 'DNI') !== false) {
                        // insertar 2 filas antes
                        $sheet->insertNewRowBefore($row, 2);

                        // aplicar estilo amarillo a la fila del DNI (se desplazó 2 filas abajo)
                        $targetRow = $row + 2;
                        $sheet->getStyle("A{$targetRow}:B{$targetRow}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'f5b91a'],
                            ],
                            'font' => [
                                'bold' => true,
                            ],
                        ]);
                    }
                }

                if ($this->detallado) {
                    $event->sheet->getDelegate()->getStyle('A5:L5')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('f5b91a');
                }

                // Unir celdas de títulos (ajusta el rango si tu vista detallada usa más columnas)
                if ($this->detallado) {
                    $cells2 = "A1:L3";
                    $event->sheet->mergeCells("A1:L1");
                    $event->sheet->mergeCells("A2:L2");
                    $event->sheet->mergeCells("A3:L3");
                }else {
                    $cells2 = "A1:G3";
                    $event->sheet->mergeCells("A1:G1");
                    $event->sheet->mergeCells("A2:G2");
                    $event->sheet->mergeCells("A3:G3");
                }
                
                $event->sheet->getDelegate()->getStyle($cells2)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($cells2)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cells2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    /**
     * Selección de vista para exportación
     */
    public function view(): View
    {
        $rango = 'DEL ' . Carbon::parse($this->fecha1)->format('d-m-Y') . ' AL ' . Carbon::parse($this->fecha2)->format('d-m-Y');

        $vista = $this->detallado
            ? 'reports.marcajesExcelDetallado'  // Vista NUEVA para el botón "EXCEL DETALLADO"
            : 'reports.marcajesExcel';          // Vista actual del "EXCEL" normal

        return view($vista, [
            'marcajes' => $this->marcajes,
            'rango'    => $rango,
            'fechai'   => $this->fecha1,
            'fechaf'   => $this->fecha2,
        ]);
    }
}
