USE IT;

-- ── Usuario de prueba ────────────────────────────────────────
INSERT INTO usuarios (nombre, identificacion, correo, telefono, clave, estado_membresia, rol, estado_usuario, fecha_registro, fecha_inicio_membresia, fecha_fin_membresia, tipo_insumos) VALUES
('Ganadería Livestock Demo', '900123456', 'demo@ganaderialivestock.co', '3142873700',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 1, 'admin', 1, NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Ganado bovino');

SET @uid = LAST_INSERT_ID();

-- ── Animales (ganado bovino) ─────────────────────────────────
INSERT INTO animales (id_usuario, titulo, descripcion, categoria, raza, pureza, sexo, tipo_animal, edad, peso, precio, tipo_precio, telefono, correo, departamento, municipio, direccion, destacado, premium, sugerido, vendido, fecha_fin, latitud, longitud, imagenes, videos) VALUES

(@uid, 'Novillo Brahman Rojo de engorde',
'Excelente novillo Brahman Rojo de 18 meses, criado en pastizales naturales de la región llanera. Muy dócil y con gran potencial de carne. Peso ideal para engorde. Certificado libre de brucelosis y aftosa.',
'Carne', 'Brahman Rojo', 'Puro registro', 'Macho', 'Bovino', '18 meses', 380.00, 4200000.00, 'unidad',
'3142873700', 'demo@ganaderialivestock.co', 'Meta', 'Villavicencio', 'Vereda La Esperanza Km 12',
1, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 4.1420, -73.6266, '[]', '[]'),

(@uid, 'Lote 20 novillas Holstein preñadas',
'Lote de 20 novillas Holstein con excelente producción láctea comprobada. Entre 2 y 3 años, todas preñadas de 5 a 7 meses. Procedentes de finca tecnificada en Cundinamarca. Con registros sanitarios al día.',
'Leche', 'Holstein', 'Cruza', 'Hembra', 'Bovino', '2-3 años', 520.00, 85000000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Cundinamarca', 'Ubaté', 'Vereda Bosavita',
1, 0, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 5.3128, -73.8177, '[]', '[]'),

(@uid, 'Toro Cebú Brahman reproductor',
'Toro reproductor Brahman Gris de 4 años con excelente mansedumbre y libido comprobado. Padre de más de 200 crías registradas. Ideal para mejoramiento genético de hatos en zona tropical.',
'Carne', 'Brahman Gris', 'Puro registro', 'Macho', 'Bovino', '4 años', 820.00, 18500000.00, 'unidad',
'3142873700', 'demo@ganaderialivestock.co', 'Córdoba', 'Montería', 'Finca El Paraíso Vía Cereté',
1, 1, 0, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 8.7479, -75.8814, '[]', '[]'),

(@uid, 'Novillos doble propósito Simmental x Cebú',
'10 novillos cruce Simmental con Cebú, entre 24 y 30 meses. Excelente conformación cárnica y potencial lechero. Adaptados al clima cálido. Manejo sanitario completo.',
'Doble propósito', 'Simmental x Cebú', 'Cruza', 'Macho', 'Bovino', '24-30 meses', 450.00, 52000000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Antioquia', 'Caucasia', 'Hacienda Los Ceibos',
0, 0, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 7.9893, -75.1978, '[]', '[]'),

(@uid, 'Vacas Gyr Lechero alta producción',
'5 vacas Gyr Lechero con producción promedio de 18 litros/día. Entre 3 y 5 años, en plena lactancia. Completamente mansas y adaptadas al ordeño mecánico. Ubicadas en Boyacá.',
'Leche', 'Gyr Lechero', 'Puro registro', 'Hembra', 'Bovino', '3-5 años', 480.00, 62000000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Boyacá', 'Tunja', 'Vereda Pirgua',
0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 5.5353, -73.3678, '[]', '[]'),

(@uid, 'Terneros destetos Angus x Brahman',
'Lote de 15 terneros destetos cruce Angus con Brahman, entre 6 y 8 meses. Excelente ganancia de peso diaria (900g/día comprobado). Vacunados y desparasitados. Listos para iniciar engorde.',
'Carne', 'Angus x Brahman', 'Cruza', 'Macho', 'Bovino', '6-8 meses', 180.00, 31500000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Casanare', 'Yopal', 'Hato El Porvenir Vía Orocué',
0, 0, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 5.3378, -72.3959, '[]', '[]'),

(@uid, 'Novillas Normando preñadas primer parto',
'8 novillas Normando de primer parto, entre 26 y 30 meses, preñadas de 4 a 6 meses. Excelente conformación, muy mansas. Procedentes de finca tecnificada del Eje Cafetero.',
'Doble propósito', 'Normando', 'Puro registro', 'Hembra', 'Bovino', '26-30 meses', 430.00, 72000000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Risaralda', 'Pereira', 'Vereda La Florida',
0, 1, 0, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 4.8087, -75.6906, '[]', '[]'),

(@uid, 'Toros Senepol adaptados a trópico bajo',
'3 toros Senepol de 3 años, raza africana adaptada perfectamente al trópico colombiano. Sin cuernos naturalmente, muy mansedumbre. Excelentes reproductores para clima cálido.',
'Carne', 'Senepol', 'Puro registro', 'Macho', 'Bovino', '3 años', 700.00, 42000000.00, 'lote',
'3142873700', 'demo@ganaderialivestock.co', 'Tolima', 'Ibagué', 'Finca La Esperanza Vía Cajamarca',
1, 0, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 4.4389, -75.2322, '[]', '[]');

-- ── Caballos ─────────────────────────────────────────────────
INSERT INTO caballos (id_usuario, titulo, descripcion, categoria, raza, sexo, edad, peso, precio, caracteristicas, telefono, correo, departamento, municipio, direccion, latitud, longitud, destacado, premium, sugerido, vendido, fecha_fin, terminos, imagenes, videos) VALUES

(@uid, 'Caballo Paso Fino Colombiano campeón',
'Ejemplar Paso Fino Colombiano campeón regional 2023. 7 años, color alazán tostado, 450kg. Excelente brio y comodidad. Con todos sus papeles y registros de la Federación Colombiana de Paso Fino.',
'Caballo Criollo Colombiano', 'Paso Fino Colombiano', 'Macho', '7 años', 450, 85000000,
'Campeón regional, brio notable, papeles al día, excelente comodidad de marcha',
'3142873700', 'demo@ganaderialivestock.co', 'Antioquia', 'Medellín', 'Club Hípico El Poblado',
6.2442, -75.5812, 1, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]'),

(@uid, 'Yegua Criollo Colombiano de vientre',
'Hermosa yegua Criollo Colombiano de 5 años, color zaino. Preñada de 4 meses de semental campeón. Excelente productora. Muy mansa y fácil de manejar. Ideal para cría.',
'Caballo Criollo Colombiano', 'Criollo Colombiano', 'Hembra', '5 años', 420, 38000000,
'Preñada de campeón, muy mansa, excelente vientre, fácil manejo',
'3142873700', 'demo@ganaderialivestock.co', 'Valle del Cauca', 'Cali', 'Hacienda El Retiro Vía Palmira',
3.4516, -76.5320, 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]'),

(@uid, 'Cuarto de Milla importado para rodeo',
'Semental Cuarto de Milla importado de Texas, 4 años, color bayo. Entrenado para rodeo y trabajo de ganado. Velocidad y agilidad excepcionales. Con papeles AQHA.',
'Cuarto de Milla', 'American Quarter Horse', 'Macho', '4 años', 520, 120000000,
'Importado USA, papeles AQHA, entrenado rodeo, velocidad excepcional',
'3142873700', 'demo@ganaderialivestock.co', 'Cundinamarca', 'Bogotá', 'Club Militar de Equitación',
4.7110, -74.0721, 1, 1, 0, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]'),

(@uid, 'Mula carguera bien amansada',
'Mula carguera de 6 años, color negro, 480kg. Excelente para trabajo en zonas de difícil acceso. Completamente mansa, obediente y con gran resistencia. Ubicada en la región cafetera.',
'Caballos Mulares', 'Mula', 'Hembra', '6 años', 480, 8500000,
'Mansa, resistente, ideal trabajo en montaña, bien amansada',
'3142873700', 'demo@ganaderialivestock.co', 'Caldas', 'Manizales', 'Vereda El Rosario',
5.0689, -75.5174, 0, 0, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]'),

(@uid, 'Caballo Árabe pura sangre importado',
'Pura sangre Árabe importado de España, 3 años, color tordo. Excelente para endurance y exposición. Con pedigree internacional certificado. Criado en Colombia desde los 8 meses.',
'Caballo Árabe', 'Árabe Pura Sangre', 'Macho', '3 años', 400, 95000000,
'Pedigree internacional, ideal endurance, criado en Colombia, excelente temperamento',
'3142873700', 'demo@ganaderialivestock.co', 'Santander', 'Bucaramanga', 'Club Campestre La Triada',
7.1193, -73.1227, 1, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]'),

(@uid, 'Percherón para trabajo agrícola',
'Hermoso percherón de 5 años, color tordo, 850kg. Ideal para trabajo agrícola pesado y transporte en zonas rurales. Completamente mansO. Con gran fuerza y resistencia.',
'Caballo Percherón', 'Percherón', 'Macho', '5 años', 850, 22000000,
'Gran fuerza, ideal trabajo agrícola, completamente manso, excelente resistencia',
'3142873700', 'demo@ganaderialivestock.co', 'Nariño', 'Pasto', 'Vereda La Cocha',
1.2136, -77.2811, 0, 0, 0, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), 1, '[]', '[]');

