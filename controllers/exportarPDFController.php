<?php
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class exportarPDFController {

    private ReservaRepository $reservaRepository;

    public function __construct() {
        $this->reservaRepository = new ReservaRepository();
    }

    public function exportarPDF() {

        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

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

        $pdf->Cell(30,  8, 'Habitacion',   1, 0, 'C', true);
        $pdf->Cell(30,  8, 'Categoria',    1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Fecha-Inicio', 1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Fecha-Fin',    1, 0, 'C', true);
        $pdf->Cell(20,  8, 'Personas',     1, 0, 'C', true);
        $pdf->Cell(35,  8, 'Precio',       1, 0, 'C', true);
        $pdf->Cell(30,  8, 'Estado',       1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 9);

        foreach ($reservas as $reservaId) {
            $pdf->Cell(30,  8, $reservaId['categoria'],                                 1, 0, 'C');
            $pdf->Cell(35,  8, date('d/m/Y', strtotime($reservaId['fecha_inicio'])),    1, 0, 'C');
            $pdf->Cell(35,  8, date('d/m/Y', strtotime($reservaId['fecha_fin'])),       1, 0, 'C');
            $pdf->Cell(20,  8, $reservaId['num_personas'],                              1, 0, 'C');
            $pdf->Cell(35,  8, '$' . number_format($reservaId['precio'], 0, ',', '.'),  1, 0, 'C');
            $pdf->Cell(30,  8, ucfirst($reservaId['estado']),                           1, 1, 'C');
        }

        $pdf->Output('mis-reservas.pdf', 'D');
        exit;
    }

    public function exportarPDFPorReserva() {

    if (!isset($_SESSION['usuario'])) {
        header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
        exit;
    }

    $idReserva = intval($_GET['id'] ?? 0);

    if ($idReserva === 0) {
        $_SESSION['resultado'] = ['error' => 'Reserva no válida.'];
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }

    $reservaId = $this->reservaRepository->getReservaPorId(
        $idReserva,
        $_SESSION['usuario']['id']
    );

    if (!$reservaId) {
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
        'Habitacion'     => 'N ' . $reservaId['num_habitacion'],
        'Categoria'      => $reservaId['categoria'],
        'Fecha-Inicio'       => date('d/m/Y', strtotime($reservaId['fecha_inicio'])),
        'Fecha-Fin'      => date('d/m/Y', strtotime($reservaId['fecha_fin'])),
        'Personas'       => $reservaId['num_personas'],
        'Metodo de pago' => $reservaId['metodo_pago'],
        'Estado'         => ucfirst($reservaId['estado']),
        'Precio total'   => '$' . number_format($reservaId['precio'], 0, ',', '.')
    ];

    foreach ($campos as $campo => $valor) {
        $pdf->Cell(95, 8, $campo, 1, 0, 'L');
        $pdf->Cell(95, 8, $valor, 1, 1, 'L');
    }

    $pdf->Output('reserva-' . $reservaId['id'] . '.pdf', 'D');
    exit;
}
}