<?php
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class exportarPDFController {

    private ReservaRepository $reservaRepository;

    public function __construct() {
        $this->reservaRepository = new ReservaRepository();
    }

    public function exportarPDF() {

        $reservas = $this->reservaRepository->getReservasPorUsuarioPDF(
            $_SESSION['usuario']['id']
        );

        $pdf = new FPDF();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->Cell(0, 10, 'Mis Reservas - Lumiere Hotels', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, 'Usuario: ' . $_SESSION['usuario']['nombre'], 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFillColor(26, 22, 16);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);

        $pdf->Cell(10,  8, '#',            1, 0, 'C', true);
        $pdf->Cell(30,  8, 'Habitacion',   1, 0, 'C', true);
        $pdf->Cell(30,  8, 'Categoria',    1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Fecha-Inicio', 1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Fecha-Fin',    1, 0, 'C', true);
        $pdf->Cell(20,  8, 'Personas',     1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Precio',       1, 0, 'C', true);
        $pdf->Cell(30,  8, 'Estado',       1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 9);

        foreach ($reservas as $r) {
            $pdf->Cell(30,  8, 'N ' . $r['num_habitacion'],                     1, 0, 'C');
            $pdf->Cell(30,  8, $r['categoria'],                                 1, 0, 'C');
            $pdf->Cell(35,  8, date('d/m/Y', strtotime($r['fecha_inicio'])),    1, 0, 'C');
            $pdf->Cell(35,  8, date('d/m/Y', strtotime($r['fecha_fin'])),       1, 0, 'C');
            $pdf->Cell(20,  8, $r['num_personas'],                              1, 0, 'C');
            $pdf->Cell(35,  8, '$' . number_format($r['precio'], 0, ',', '.'),  1, 0, 'C');
            $pdf->Cell(30,  8, ucfirst($r['estado']),                           1, 1, 'C');
        }

        $pdf->Output('mis-reservas.pdf', 'D');
        exit;
    }

    public function exportarPDFPorReserva() {

    $idReserva = intval($_GET['id'] ?? 0);

    if ($idReserva === 0) {
        $_SESSION['resultado'] = ['error' => 'Reserva no válida.'];
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }

    $r = $this->reservaRepository->getReservaPorId(
        $idReserva,
        $_SESSION['usuario']['id']
    );

    if (!$r) {
        $_SESSION['resultado'] = ['error' => 'Reserva no encontrada.'];
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Lumiere Hotels - Comprobante de Reserva', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 8, 'Huesped: ' . $_SESSION['usuario']['nombre'], 0, 1);
    $pdf->Cell(0, 8, 'Email: '   . $_SESSION['usuario']['email'],  0, 1);
    $pdf->Ln(5);

    $pdf->SetFillColor(26, 22, 16);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 9);

    $pdf->Cell(95, 8, 'Campo',   1, 0, 'C', true);
    $pdf->Cell(95, 8, 'Detalle', 1, 1, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);

    $campos = [
        'N Reserva'      => $r['id'],
        'Habitacion'     => 'N ' . $r['num_habitacion'],
        'Categoria'      => $r['categoria'],
        'Check-in'       => date('d/m/Y', strtotime($r['fecha_inicio'])),
        'Check-out'      => date('d/m/Y', strtotime($r['fecha_fin'])),
        'Personas'       => $r['num_personas'],
        'Metodo de pago' => $r['metodo_pago'],
        'Estado'         => ucfirst($r['estado']),
        'Precio total'   => '$' . number_format($r['precio'], 0, ',', '.')
    ];

    foreach ($campos as $campo => $valor) {
        $pdf->Cell(95, 8, $campo, 1, 0, 'L');
        $pdf->Cell(95, 8, $valor, 1, 1, 'L');
    }

    $pdf->Output('reserva-' . $r['id'] . '.pdf', 'D');
    exit;
}
}