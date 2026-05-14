<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class exportarExcelController {

    private ReservaRepository $reservaRepository;

    public function __construct() {
        $this->reservaRepository = new ReservaRepository();
    }

    public function exportarExcel() {

        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

        $reservas = $this->reservaRepository->getReservasPorUsuarioPDF(
            $_SESSION['usuario']['id']
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mis Reservas');

        // Título
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'Mis Reservas - Lumiere Hotels');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1610']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Subtítulo
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Usuario: ' . $_SESSION['usuario']['nombre']);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['rgb' => '8B7355']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Cabeceras
        $cabeceras = ['A' => 'Habitación', 'B' => 'Categoría', 'C' => 'Fecha Inicio',
                      'D' => 'Fecha Fin',  'E' => 'Personas',  'F' => 'Precio', 'G' => 'Estado'];

        foreach ($cabeceras as $col => $titulo) {
            $sheet->setCellValue($col . '4', $titulo);
        }

        $sheet->getStyle('A4:G4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1610']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);

        // Datos
        $fila = 5;
        foreach ($reservas as $r) {
            $sheet->setCellValue('A' . $fila, 'N° ' . $r['num_habitacion']);
            $sheet->setCellValue('B' . $fila, $r['categoria']);
            $sheet->setCellValue('C' . $fila, date('d/m/Y', strtotime($r['fecha_inicio'])));
            $sheet->setCellValue('D' . $fila, date('d/m/Y', strtotime($r['fecha_fin'])));
            $sheet->setCellValue('E' . $fila, (int) $r['num_personas']);
            $sheet->setCellValue('F' . $fila, '$' . number_format($r['precio'], 0, ',', '.'));
            $sheet->setCellValue('G' . $fila, ucfirst($r['estado']));

            $bgColor = ($fila % 2 === 0) ? 'FAF8F4' : 'FFFFFF';
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E8DDC9']]],
            ]);

            $fila++;
        }

        // Anchos de columna
        foreach (['A' => 14, 'B' => 18, 'C' => 16, 'D' => 16, 'E' => 12, 'F' => 16, 'G' => 14] as $col => $ancho) {
            $sheet->getColumnDimension($col)->setWidth($ancho);
        }

        // Descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="mis-reservas.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
