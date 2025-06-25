-- Usamos la base de datos correcta
USE comunidades_db;

-- Desactivar temporalmente la verificación de claves foráneas para una inserción masiva y sin errores de orden.
SET FOREIGN_KEY_CHECKS=0;

-- Vaciar todas las tablas para un inicio limpio (en orden inverso de dependencia)
TRUNCATE TABLE actividad_beneficiario;
TRUNCATE TABLE actividades;
TRUNCATE TABLE productos;
TRUNCATE TABLE componentes;
TRUNCATE TABLE ejes;
TRUNCATE TABLE usuario_roles;
TRUNCATE TABLE usuarios;
TRUNCATE TABLE roles;
TRUNCATE TABLE organizaciones;

-- Reactivar la verificación
SET FOREIGN_KEY_CHECKS=1;


-- 1. Poblar Roles
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'admin', 'Acceso total al sistema.'),
(2, 'Gestor', 'Puede gestionar productos y actividades.'),
(3, 'Consultor', 'Solo puede ver la información y reportes.');

-- 2. Poblar Organizaciones
INSERT INTO `organizaciones` (`id`, `nombre`) VALUES
(1, 'Plan Estratégico de Juárez'),
(2, 'FCFN'),
(3, 'Desafio'),
(4, 'Gobierno Municipal'),
(5, 'Universidad Autónoma de Ciudad Juárez');

-- 3 Reemplaza la línea INSERT de usuarios con esta:
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `organizacion_id`, `puesto`) VALUES
(1, 'David García', 'dgarcia@planjuarez.org', '$2y$10$C5bE2nK6vN5iQ3Z.eG5y7uM2w9gG1rVlO8m/8rJg7kS3eF4zH2h.O', 1, 'Coordinador General'), -- pass: Gagd891220
(2, 'Laura Martinez', 'laura.m@desafio.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'Gestora de Proyectos'), -- pass: password
(3, 'Carlos Herrera', 'carlos.h@uacj.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, 'Investigador Externo'); -- pass: password
-- 4. Asignar Roles a Usuarios
INSERT INTO `usuario_roles` (`usuario_id`, `rol_id`) VALUES
(1, 1), -- David García es Admin
(2, 2), -- Laura Martinez es Gestora
(3, 3); -- Carlos Herrera es Consultor

-- 5. Poblar Jerarquía Estratégica
INSERT INTO `ejes` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Desarrollo Social Inclusivo', 'Fomentar la cohesión social y mejorar la calidad de vida de los habitantes.'),
(2, 'Desarrollo Económico Sostenible', 'Impulsar la competitividad, el emprendimiento y la innovación en la región.');

INSERT INTO `componentes` (`id`, `eje_id`, `nombre`) VALUES
(1, 1, 'Cultura y Deporte'),
(2, 1, 'Salud Comunitaria'),
(3, 2, 'Capacitación para el Empleo'),
(4, 2, 'Fomento a PyMEs');

INSERT INTO `productos` (`id`, `componente_id`, `tipo_producto`, `nombre`) VALUES
(1, 1, 'Evento Comunitario', 'Programa de Activación Física en Parques'),
(2, 1, 'Contenido Cultural', 'Murales Comunitarios "Colores de mi Barrio"'),
(3, 3, 'Proceso de Capacitación', 'Certificación en Habilidades Digitales para Jóvenes');

-- 6. Poblar Actividades
-- Actividades para el Producto 1
INSERT INTO `actividades` (`producto_id`, `nombre`, `fecha_inicio`, `fecha_fin`, `responsable_id`, `estatus`, `meta`, `indicador`) VALUES
(1, 'Clase de Yoga al Amanecer', '2025-07-20 07:00:00', '2025-07-20 08:00:00', 2, 'Realizada', '20 asistentes', 'Lista de asistencia'),
(1, 'Torneo de Voleibol Playero', '2025-08-10 09:00:00', '2025-08-10 14:00:00', 2, 'Programada', '12 equipos inscritos', 'Formularios de inscripción'),
(1, 'Caminata Recreativa 5k', '2025-09-05 08:00:00', '2025-09-05 10:00:00', 1, 'Cancelada', '100 participantes', 'Número de registros en línea');
-- Actividades para el Producto 2
INSERT INTO `actividades` (`producto_id`, `nombre`, `fecha_inicio`, `fecha_fin`, `responsable_id`, `estatus`, `meta`, `indicador`) VALUES
(2, 'Reunión de Diseño con Artistas Locales', '2025-07-25 18:00:00', '2025-07-25 20:00:00', 1, 'Realizada', 'Definir 3 bocetos finalistas', 'Acta de reunión con acuerdos'),
(2, 'Pinta de Mural en Parque Revolución', '2025-08-15 09:00:00', '2025-08-18 17:00:00', 1, 'Programada', 'Completar 1 mural de 20x5 metros', 'Reporte fotográfico del avance');
-- Actividades para el Producto 3
INSERT INTO `actividades` (`producto_id`, `nombre`, `fecha_inicio`, `fecha_fin`, `responsable_id`, `estatus`, `meta`, `indicador`) VALUES
(3, 'Taller de Introducción a HTML y CSS', '2025-09-01 16:00:00', '2025-09-01 18:00:00', 2, 'Programada', '25 jóvenes inscritos', 'Lista de inscripción'),
(3, 'Curso de Marketing Digital', '2025-09-08 16:00:00', '2025-09-12 18:00:00', 2, 'Programada', '20 jóvenes completan el curso', 'Certificados emitidos');

-- 7. Poblar Beneficiarios
INSERT INTO `beneficiarios` (`nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `sexo`, `curp`, `telefono`, `colonia`) VALUES
('Ana', 'Gómez', 'Solis', '1995-03-12', 'Mujer', 'GOSA950312MDFRXX01', '6561112233', 'Centro'),
('Luis', 'Hernández', 'García', '2001-07-24', 'Hombre', 'HEGL010724HCHXXX02', '6562223344', 'El Granjero'),
('Sofía', 'López', 'Mendoza', '1988-12-01', 'Mujer', 'LOMS881201MCHXXX03', '6563334455', 'Salvarcar'),
('Miguel', 'Ramírez', 'Flores', '1999-01-18', 'Hombre', 'RAFM990118HCHXXX04', '6564445566', 'Horizontes del Sur'),
('Valeria', 'Torres', 'Peña', '2004-06-30', 'Mujer', 'TOPV040630MCHXXX05', '6565556677', 'Aztecas'),
('Jorge', 'Díaz', 'Castillo', '1979-09-05', 'Hombre', 'DICJ790905HCHXXX06', '6566667788', 'Zaragoza');

-- 8. Poblar Asistencia (Vincular Beneficiarios a Actividades Realizadas)
INSERT INTO `actividad_beneficiario` (`actividad_id`, `beneficiario_id`, `fecha_asistencia`, `observaciones`) VALUES
-- Asistentes a Clase de Yoga (ID 1)
(1, 1, '2025-07-20', 'Muy entusiasta'),
(1, 3, '2025-07-20', 'Primera vez en una clase de yoga'),
(1, 6, '2025-07-20', ''),
-- Asistentes a Reunión de Diseño (ID 4)
(4, 2, '2025-07-25', 'Propuso ideas interesantes'),
(4, 4, '2025-07-25', 'Llegó como oyente'),
(4, 5, '2025-07-25', 'Interesada en participar en la pinta');