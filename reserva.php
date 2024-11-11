<?php
include 'db_config.php';
require 'vendor/autoload.php'; // Para PHPMailer y FPDF

$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$email = $data['email'];
$dni = $data['dni'];
$telefono = $data['telefono'];
$fechaIngreso = $data['fechaIngreso'];
$fechaEgreso = $data['fechaEgreso'];

// Verificar disponibilidad de las fechas
$consulta = $conn->prepare("SELECT * FROM reservas WHERE (fecha_ingreso <= ? AND fecha_egreso >= ?) OR (fecha_ingreso <= ? AND fecha_egreso >= ?)");
$consulta->bind_param("ssss", $fechaEgreso, $fechaIngreso, $fechaIngreso, $fechaEgreso);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode(["mensaje" => "Las fechas seleccionadas ya están ocupadas."]);
} else {
    // Realizar la reserva
    $insercion = $conn->prepare("INSERT INTO reservas (departamento_id, fecha_ingreso, fecha_egreso, cliente_nombre, cliente_email) VALUES (?, ?, ?, ?, ?)");
    $departamentoId = 1; // Suponiendo un ID fijo o manejado de otra forma
    $nombreCompleto = "$nombre $apellido";
    $insercion->bind_param("issss", $departamentoId, $fechaIngreso, $fechaEgreso, $nombreCompleto, $email);
    $insercion->execute();

    // Generar PDF de confirmación usando FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Confirmacion de Reserva', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Nombre: $nombre $apellido", 0, 1);
    $pdf->Cell(0, 10, "DNI: $dni", 0, 1);
    $pdf->Cell(0, 10, "Telefono: $telefono", 0, 1);
    $pdf->Cell(0, 10, "Fecha de Ingreso: $fechaIngreso", 0, 1);
    $pdf->Cell(0, 10, "Fecha de Egreso: $fechaEgreso", 0, 1);
    $pdf->Output('F', 'confirmacion_reserva.pdf');

    // Enviar el correo con PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.tudominio.com'; // Servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'tuemail@tudominio.com'; // Tu correo
    $mail->Password = 'tucontraseña'; // Tu contraseña
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tuemail@tudominio.com', 'Alquiler Departamentos');
    $mail->addAddress($email, "$nombre $apellido");

    $mail->Subject = 'Confirmacion de Reserva';
    $mail->Body    = "Hola $nombre, \n\nSu reserva ha sido confirmada. Adjunto encontrará el comprobante con los detalles de su estadía.";
    $mail->addAttachment('confirmacion_reserva.pdf');

    if ($mail->send()) {
        echo json_encode(["mensaje" => "Reserva realizada con éxito y confirmación enviada por correo."]);
    } else {
        echo json_encode(["mensaje" => "Reserva realizada, pero el correo no pudo enviarse."]);
    }

    unlink('confirmacion_reserva.pdf'); // Eliminar el PDF después de enviarlo
}

$conn->close();
?>
