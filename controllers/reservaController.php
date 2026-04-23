<?php
require_once __DIR__ . '/../models/ReservaRepository.php';

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
            'id_metodo_pago'=> $idMetodoPago
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