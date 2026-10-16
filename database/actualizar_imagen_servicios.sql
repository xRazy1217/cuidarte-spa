-- Cambiar el campo imagen de servicios a LONGBLOB para guardar base64
ALTER TABLE servicios MODIFY COLUMN imagen LONGBLOB;
