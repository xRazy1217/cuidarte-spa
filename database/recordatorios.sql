-- Tabla para rastrear recordatorios enviados
CREATE TABLE IF NOT EXISTS `recordatorios_enviados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int NOT NULL,
  `tipo` enum('recordatorio_2h','recordatorio_1h','confirmacion') COLLATE utf8mb4_unicode_ci DEFAULT 'recordatorio_2h',
  `enviado_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reserva_tipo` (`reserva_id`, `tipo`),
  KEY `reserva_id` (`reserva_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
