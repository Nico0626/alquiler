CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10, 2)
);

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departamento_id INT,
    fecha_ingreso DATE,
    fecha_egreso DATE,
    cliente_nombre VARCHAR(100),
    cliente_email VARCHAR(100),
    cliente_dni VARCHAR(20),
    cliente_telefono VARCHAR(20),
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
);
