<?php

class UserRepository {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function emailExiste(string $email): bool {
        $stmt = $this->db->prepare("SELECT id_usuarios FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->rowCount() > 0;
    }

public function buscarPorEmail(string $email): ?array {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();
    return $usuario ?: null;
}

    public function documentoExiste(string $numero): bool {
        $stmt = $this->db->prepare("SELECT id_usuarios FROM users WHERE numero_documento = :numero");
        $stmt->execute([':numero' => $numero]);
        return $stmt->rowCount() > 0;
    }

    public function crear(array $datos): bool {
        $stmt = $this->db->prepare("
            INSERT INTO users (nombre, tipo_documento_id, numero_documento, email, contrasena)
            VALUES (:nombre, :tipo_documento_id, :numero_documento, :email, :contrasena)
        ");

        return $stmt->execute([
            ':nombre'           => $datos['nombre'],
            ':tipo_documento_id'=> $datos['tipo_documento_id'],
            ':numero_documento' => $datos['numero_documento'],
            ':email'            => $datos['email'],
            ':contrasena'       => password_hash($datos['contrasena'], PASSWORD_BCRYPT)
        ]);
    }
}