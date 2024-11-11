<?php
include 'db_config.php';

$sql = "SELECT * FROM departamentos";
$result = $conn->query($sql);

$departamentos = [];

while ($row = $result->fetch_assoc()) {
    $departamento = [
        "id" => $row['id'],
        "nombre" => $row['nombre'],
        "descripcion" => $row['descripcion'],
        "precio" => $row['precio']
    ];
    $departamentos[] = $departamento;
}

echo json_encode($departamentos);
?>
