async function cargarDepartamentos() {
    const respuesta = await fetch('consulta_reservas.php');
    const departamentos = await respuesta.json();
    
    const catalogo = document.getElementById("catalogo");
    catalogo.innerHTML = "";

    departamentos.forEach(depto => {
        let departamentoHTML = `<div>
            <h3>${depto.nombre}</h3>
            <p>${depto.descripcion}</p>
            <p>Precio: $${depto.precio}</p>
        </div>`;
        catalogo.innerHTML += departamentoHTML;
    });
}

async function realizarReserva() {
    const nombre = document.getElementById("nombre").value;
    const apellido = document.getElementById("apellido").value;
    const email = document.getElementById("email").value;
    const dni = document.getElementById("dni").value;
    const telefono = document.getElementById("telefono").value;
    const fechaIngreso = document.getElementById("fechaIngreso").value;
    const fechaEgreso = document.getElementById("fechaEgreso").value;

    const respuesta = await fetch('reserva.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, apellido, email, dni, telefono, fechaIngreso, fechaEgreso })
    });

    const resultado = await respuesta.json();
    alert(resultado.mensaje);
}

window.onload = cargarDepartamentos;
