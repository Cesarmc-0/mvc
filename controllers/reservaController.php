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

        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

        $categorias  = $this->reservaRepository->getCategorias();
        $metodosPago = $this->reservaRepository->getMetodosPago();

        include_once $pagina;
    }

    public function getFormUpdateReserva($pagina) {

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

    $reserva     = $this->reservaRepository->getReservaPorId($idReserva, $_SESSION['usuario']['id']);
    $categorias  = $this->reservaRepository->getCategorias();
    $metodosPago = $this->reservaRepository->getMetodosPago();

    if (!$reserva) {
        $_SESSION['resultado'] = ['error' => 'Reserva no encontrada.'];
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }

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
   

    // Ver mis reservas
    public function getMisReservas($pagina) {

        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

    $reservas = $this->reservaRepository->getReservasPorUsuario(
        $_SESSION['usuario']['id']
    );
    include_once $pagina;
    }
    // Cancelar reserva
public function cancelarReserva() {

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

    if (!isset($_SESSION['usuario'])) {
        header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
        exit;
    }

    $idHabitacion = intval($_POST['id_habitacion']  ?? 0);
    $fechaInicio  = trim($_POST['fecha_inicio']     ?? '');
    $fechaFin     = trim($_POST['fecha_fin']        ?? '');
    $numPersonas  = intval($_POST['num_personas']   ?? 0);
    $idMetodoPago = intval($_POST['id_metodo_pago'] ?? 0);

    // =========================
    // VALIDACIONES
    // =========================
    $errores = $this->validarDatosReserva([
        'id_habitacion'  => $idHabitacion,
        'fecha_inicio'   => $fechaInicio,
        'fecha_fin'      => $fechaFin,
        'num_personas'   => $numPersonas,
        'id_metodo_pago' => $idMetodoPago
    ]);

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
    $dias         = (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400;
    $habitaciones = $this->reservaRepository->getHabitacionesDisponibles();
    $precio       = 0;

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
        'id_usuario'     => $_SESSION['usuario']['id'],
        'id_habitacion'  => $idHabitacion,
        'fecha_inicio'   => $fechaInicio,
        'fecha_fin'      => $fechaFin,
        'num_personas'   => $numPersonas,
        'precio'         => $precio,
        'id_metodo_pago' => $idMetodoPago
    ]);

    if ($creada) {
        $_SESSION['resultado'] = ['success' => 'Reserva creada correctamente.'];
    } else {
        $_SESSION['resultado'] = ['error' => 'Error al crear la reserva.'];
    }

    header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
    exit;
}

    public function updateReserva() {

    if (!isset($_SESSION['usuario'])) {
        header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
        exit;
    }

    $idReserva    = intval($_POST['id_reserva']     ?? 0);
    $idHabitacion = intval($_POST['id_habitacion']  ?? 0);
    $fechaInicio  = trim($_POST['fecha_inicio']     ?? '');
    $fechaFin     = trim($_POST['fecha_fin']        ?? '');
    $numPersonas  = intval($_POST['num_personas']   ?? 0);
    $idMetodoPago = intval($_POST['id_metodo_pago'] ?? 0);

    // =========================
    // VALIDACIONES
    // =========================
    $errores = $this->validarDatosReserva([
        'id_habitacion'  => $idHabitacion,
        'fecha_inicio'   => $fechaInicio,
        'fecha_fin'      => $fechaFin,
        'num_personas'   => $numPersonas,
        'id_metodo_pago' => $idMetodoPago
    ]);

    if (!empty($errores)) {
        $_SESSION['resultado'] = $errores;
        header('Location: ' . SITE_URL . 'index.php?action=getFormUpdateReserva&id=' . $idReserva);
        exit;
    }

    // =========================
    // CALCULAR PRECIO
    // =========================
    $dias         = (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400;
    $habitaciones = $this->reservaRepository->getHabitacionesDisponibles();
    $precio       = 0;

    foreach ($habitaciones as $hab) {
        if ($hab['id'] == $idHabitacion) {
            $precio = $hab['precio'] * $dias;
            break;
        }
    }

    // =========================
    // ACTUALIZAR
    // =========================
    $actualizado = $this->reservaRepository->actualizarReserva(
        $idReserva,
        $_SESSION['usuario']['id'],
        [
            'id_habitacion'  => $idHabitacion,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'num_personas'   => $numPersonas,
            'id_metodo_pago' => $idMetodoPago,
            'precio'         => $precio
        ]
    );

    if ($actualizado) {
        $_SESSION['resultado'] = ['success' => 'Reserva actualizada correctamente.'];
    } else {
        $_SESSION['resultado'] = ['error' => 'Error al actualizar la reserva.'];
    }

    header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
    exit;
}

    private function validarDatosReserva(array $datos) : array{
        $errores = [];

    if ($datos['id_habitacion'] === 0) {
        $errores[] = "Selecciona una habitación.";
    }

    if (empty($datos['fecha_inicio'])) {
        $errores[] = "La fecha de inicio es obligatoria.";
    }

    if (empty($datos['fecha_fin'])) {
        $errores[] = "La fecha de fin es obligatoria.";
    }

    if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin']) && $datos['fecha_inicio'] >= $datos['fecha_fin']) {
        $errores[] = "La fecha de fin debe ser mayor a la de inicio.";
    }

    if (!empty($datos['fecha_inicio']) && $datos['fecha_inicio'] < date('Y-m-d')) {
        $errores[] = "La fecha de inicio no puede ser en el pasado.";
    }

    if ($datos['num_personas'] === 0) {
        $errores[] = "El número de personas es obligatorio.";
    }

    if ($datos['id_metodo_pago'] === 0) {
        $errores[] = "Selecciona un método de pago.";
    }

    return $errores;
    }
}