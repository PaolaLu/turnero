<?php

namespace App\Enums;

/**
 * Roles posibles de un usuario del turnero.
 *
 * Son fijos (no se crean desde la interfaz), por eso usamos un enum de PHP
 * guardado como texto en la columna users.role, en lugar de un paquete de permisos.
 */
enum Role: string
{
    /** Dueño o encargado del negocio: configura todo. */
    case Admin = 'admin';

    /** Persona que atiende los turnos (peluquero, médico, etc.). */
    case Profesional = 'profesional';

    /** Persona que reserva turnos. Es el rol de todo usuario que se registra solo. */
    case Cliente = 'cliente';

    /**
     * Nombre para mostrar en pantalla.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Profesional => 'Profesional',
            self::Cliente => 'Cliente',
        };
    }
}
