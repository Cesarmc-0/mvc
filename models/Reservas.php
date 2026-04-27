<?php

class ReservaRepository {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Obtener habitaciones disponibles
    public function getHabitacionesDisponibles(): array {
        $stmt = $this->db->prepare("
            SELECT h.id, h.num_habitacion, h.num_camas, h.max_personas, 
                   h.descripcion, h.precio, c.nombre AS categoria
            FROM habitaciones h
            INNER JOIN categorias c ON h.id_categoria = c.id
            WHERE h.id_estado = 1
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getHabitacionesPorCategoria(int $idCategoria, string $fechaInicio, string $fechaFin): array {
    $stmt = $this->db->prepare("
        SELECT h.id, h.num_habitacion, h.num_camas,
               h.max_personas, h.descripcion, h.precio,
               c.nombre AS categoria
        FROM habitaciones h
        INNER JOIN categorias c ON h.id_categoria = c.id
        WHERE h.id_categoria = :id_categoria
        AND h.id_estado = 1
        AND h.id NOT IN (
            SELECT id_habitacion FROM reservas
            WHERE id_estado != 5
            AND (fecha_inicio < :fecha_fin AND fecha_fin > :fecha_inicio)
        )
    ");
    $stmt->execute([
        ':id_categoria' => $idCategoria,
        ':fecha_inicio' => $fechaInicio,
        ':fecha_fin'    => $fechaFin
    ]);
    return $stmt->fetchAll();
}
    // Obtener reservas de un usuario
public function getReservasPorUsuario(int $idUsuario): array {
    $stmt = $this->db->prepare("
        SELECT 
            r.id,
            h.num_habitacion,
            c.nombre      AS categoria,
            r.fecha_inicio,
            r.fecha_fin,
            r.num_personas,
            r.precio,
            e.nombre      AS estado,
            m.nombre      AS metodo_pago
        FROM reservas r
        INNER JOIN habitaciones h ON r.id_habitacion = h.id
        INNER JOIN categorias   c ON h.id_categoria  = c.id
        INNER JOIN estados      e ON r.id_estado      = e.id
        INNER JOIN metodos_pago m ON r.id_metodo_pago = m.id
        WHERE r.id_usuario = :id_usuario
        ORDER BY r.created_at ASC
    ");
    $stmt->execute([':id_usuario' => $idUsuario]);
    return $stmt->fetchAll();
}
    public function getReservasPorUsuarioPDF(int $idUsuario): array {
    $stmt = $this->db->prepare("
        SELECT 
            r.id,
            h.num_habitacion,
            c.nombre      AS categoria,
            r.fecha_inicio,
            r.fecha_fin,
            r.num_personas,
            r.precio,
            e.nombre      AS estado
        FROM reservas r
        INNER JOIN habitaciones h ON r.id_habitacion = h.id
        INNER JOIN categorias   c ON h.id_categoria  = c.id
        INNER JOIN estados      e ON r.id_estado      = e.id
        WHERE r.id_usuario = :id_usuario
        ORDER BY r.created_at ASC
    ");
    $stmt->execute([':id_usuario' => $idUsuario]);
    return $stmt->fetchAll();
}

    // Cancelar reserva (Soft Delete)
    public function cancelarReserva(int $idReserva, int $idUsuario): bool {
        $stmt = $this->db->prepare("
            UPDATE reservas 
            SET id_estado = 5
            WHERE id = :id AND id_usuario = :id_usuario
        ");
        return $stmt->execute([
            ':id'         => $idReserva,
            ':id_usuario' => $idUsuario
        ]);
    }

    public function pendienteReserva(int $idReserva, int $idUsuario): bool {
    $stmt = $this->db->prepare("
        UPDATE reservas 
        SET id_estado = 3
        WHERE id = :id AND id_usuario = :id_usuario
    ");
    return $stmt->execute([
        ':id'         => $idReserva,
        ':id_usuario' => $idUsuario
    ]);
}

    // Obtener métodos de pago
    public function getMetodosPago(): array {
        $stmt = $this->db->prepare("SELECT id, nombre FROM metodos_pago");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Verificar si habitación está disponible en esas fechas
    public function habitacionDisponible(int $idHabitacion, string $fechaInicio, string $fechaFin): bool {
        $stmt = $this->db->prepare("
            SELECT id FROM reservas
            WHERE id_habitacion = :id_habitacion
            AND id_estado != 5
            AND (fecha_inicio < :fecha_fin AND fecha_fin > :fecha_inicio)
        ");
        $stmt->execute([
            ':id_habitacion' => $idHabitacion,
            ':fecha_inicio'  => $fechaInicio,
            ':fecha_fin'     => $fechaFin
        ]);
        return $stmt->rowCount() === 0;
    }

    // Crear reserva
    public function crear(array $datos): bool {
        $stmt = $this->db->prepare("
            INSERT INTO reservas (id_usuario, id_habitacion, fecha_inicio, fecha_fin, num_personas, id_estado, precio, id_metodo_pago)
            VALUES (:id_usuario, :id_habitacion, :fecha_inicio, :fecha_fin, :num_personas, :id_estado, :precio, :id_metodo_pago)
        ");

        return $stmt->execute([
            ':id_usuario'    => $datos['id_usuario'],
            ':id_habitacion' => $datos['id_habitacion'],
            ':fecha_inicio'  => $datos['fecha_inicio'],
            ':fecha_fin'     => $datos['fecha_fin'],
            ':num_personas'  => $datos['num_personas'],
            ':id_estado'     => 4, 
            ':precio'        => $datos['precio'],
            ':id_metodo_pago'=> $datos['id_metodo_pago']
        ]);
    }
}