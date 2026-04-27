<?php
require_once __DIR__ . '/../models/Reservas.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class reservaController {

    private ReservaRepository $reservaRepository;

    public function __construct() {
        $this->reservaRepository = new ReservaRepository();
    }

    // Ver formulario de reserva
    public function getFormCreateReserva($pagina) {
        $habitaciones = $this->reservaRepository->getHabitacionesDisponibles();
        $metodosPago  = $this->reservaRepository->getMetodosPago();
        include_once $pagina;
    }

    public function getHabitacionesPorCategoria() {
    $idCategoria = intval($_GET['id_categoria'] ?? 0);
    $fechaInicio = $_GET['fecha_inicio'] ?? '';
    $fechaFin    = $_GET['fecha_fin']    ?? '';

    if ($idCategoria === 0 || empty($fechaInicio) || empty($fechaFin)) {
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    $habitaciones = $this->reservaRepository->getHabitacionesPorCategoria(
        $idCategoria, $fechaInicio, $fechaFin
    );

    echo json_encode($habitaciones);
    exit;
}
    public function exportarPDF() {
    

    $reservas = $this->reservaRepository->getReservasPorUsuarioPDF(
        $_SESSION['usuario']['id']
    );

    $pdf = new FPDF();
    $pdf->AddPage('L'); // L = horizontal
    $pdf->SetFont('Arial', 'B', 14);

    // Título
    $pdf->Cell(0, 10, 'Mis Reservas - Lumiere Hotels', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 8, 'Usuario: ' . $_SESSION['usuario']['nombre'], 0, 1, 'C');
    $pdf->Ln(5);

    // Cabecera de tabla
    $pdf->SetFillColor(26, 22, 16);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 9);

    $pdf->Cell(10,  8, '#',           1, 0, 'C', true);
    $pdf->Cell(30,  8, 'Habitacion',  1, 0, 'C', true);
    $pdf->Cell(30,  8, 'Categoria',   1, 0, 'C', true);
    $pdf->Cell(35,  8, 'Fecha-Inicio',    1, 0, 'C', true);
    $pdf->Cell(35,  8, 'Fecha-Fin',   1, 0, 'C', true);
    $pdf->Cell(20,  8, 'Personas',    1, 0, 'C', true);
    $pdf->Cell(35,  8, 'Precio',      1, 0, 'C', true);
    $pdf->Cell(30,  8, 'Estado',      1, 1, 'C', true);

    // Datos
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

    // Ver mis reservas
    public function getMisReservas($pagina) {
    $reservas = $this->reservaRepository->getReservasPorUsuario(
        $_SESSION['usuario']['id']
    );
    include_once $pagina;
}
    // Cancelar reserva
public function cancelarReserva() {
    $idReserva = intval($_GET['id'] ?? 0);

    if ($idReserva === 0) {
        $_SESSION['resultado'] = ['error' => 'Reserva no válida.'];
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }

    $cancelada = $this->reservaRepository->cancelarReserva(
        $idReserva,
        $_SESSION['usuario']['id']
    );

    if ($cancelada) {
        $_SESSION['resultado'] = ['success' => 'Reserva cancelada correctamente.'];
    } else {
        $_SESSION['resultado'] = ['error' => 'No se pudo cancelar la reserva.'];
    }

    header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
    exit;
}
    // Procesar reserva
    public function createReserva() {

        $errores = [];

        $idHabitacion = intval($_POST['id_habitacion']  ?? 0);
        $fechaInicio  = trim($_POST['fecha_inicio']     ?? '');
        $fechaFin     = trim($_POST['fecha_fin']        ?? '');
        $numPersonas  = intval($_POST['num_personas']   ?? 0);
        $idMetodoPago = intval($_POST['id_metodo_pago'] ?? 0);

        // =========================
        // VALIDACIONES
        // =========================
        if ($idHabitacion === 0) {
            $errores[] = "Selecciona una habitación.";
        }

        if (empty($fechaInicio)) {
            $errores[] = "La fecha de inicio es obligatoria.";
        }

        if (empty($fechaFin)) {
            $errores[] = "La fecha de fin es obligatoria.";
        }

        if (!empty($fechaInicio) && !empty($fechaFin) && $fechaInicio >= $fechaFin) {
            $errores[] = "La fecha de fin debe ser mayor a la de inicio.";
        }

        if (!empty($fechaInicio) && $fechaInicio < date('Y-m-d')) {
            $errores[] = "La fecha de inicio no puede ser en el pasado.";
        }

        if ($numPersonas === 0) {
            $errores[] = "El número de personas es obligatorio.";
        }

        if ($idMetodoPago === 0) {
            $errores[] = "Selecciona un método de pago.";
        }

        // =========================
        // VERIFICAR DISPONIBILIDAD
        // =========================
        if (empty($errores)) {
            if (!$this->reservaRepository->habitacionDisponible($idHabitacion, $fechaInicio, $fechaFin)) {
                $errores[] = "La habitación no está disponible en esas fechas.";
            }
        }

        // =========================
        // RETORNAR ERRORES
        // =========================
        if (!empty($errores)) {
            $_SESSION['resultado'] = $errores;
            header('Location: ' . SITE_URL . 'index.php?action=getFormCreateReserva');
            exit;
        }

        // =========================
        // CALCULAR PRECIO
        // =========================
        $dias   = (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400;
        $habitaciones = $this->reservaRepository->getHabitacionesDisponibles();
        $precio = 0;

        foreach ($habitaciones as $hab) {
            if ($hab['id'] == $idHabitacion) {
                $precio = $hab['precio'] * $dias;
                break;
            }
        }

        // =========================
        // CREAR RESERVA
        // =========================
        $creada = $this->reservaRepository->crear([
            'id_usuario'    => $_SESSION['usuario']['id'],
            'id_habitacion' => $idHabitacion,
            'fecha_inicio'  => $fechaInicio,
            'fecha_fin'     => $fechaFin,
            'num_personas'  => $numPersonas,
            'precio'        => $precio,
            'id_metodo_pago'=> $idMetodoPago,
        ]);

        if ($creada) {
            $_SESSION['resultado'] = ['success' => 'Reserva creada correctamente.'];
        } else {
            $_SESSION['resultado'] = ['error' => 'Error al crear la reserva.'];
        }

        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }
}