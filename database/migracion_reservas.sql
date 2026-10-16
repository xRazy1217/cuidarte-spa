-- Migración: Nuevo sistema de reservas con aprobación admin
-- Fecha: 2026-03-18

-- 1. Modificar tabla reservas
ALTER TABLE `reservas` 
MODIFY COLUMN `estado` ENUM('pendiente_confirmacion','confirmada','rechazada','cancelada') DEFAULT 'pendiente_confirmacion',
ADD COLUMN `notas_admin` TEXT NULL AFTER `notas`,
ADD COLUMN `fecha_sugerida` DATE NULL AFTER `notas_admin`,
MODIFY COLUMN `horario_id` INT NULL;

-- 2. Crear índices para mejor rendimiento
ALTER TABLE `reservas` ADD INDEX `idx_estado` (`estado`);
ALTER TABLE `reservas` ADD INDEX `idx_servicio_estado` (`servicio_id`, `estado`);
ALTER TABLE `reservas` ADD INDEX `idx_created_at` (`created_at`);
