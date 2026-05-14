document.addEventListener('DOMContentLoaded', () => {

    const tipoHabitacion = document.getElementById('id_categoria');
    const numeroHabitacion = document.getElementById('id_habitacion');

    if (tipoHabitacion.value !== '') {
        tipoHabitacion.dispatchEvent(new Event('change'));
    }

    tipoHabitacion.addEventListener('change', async () => {

        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        if (
            tipoHabitacion.value === '' ||
            fechaInicio === '' ||
            fechaFin === ''
        ) {
            numeroHabitacion.innerHTML =
                '<option value="">Selecciona fechas y tipo</option>';

            return;
        }

        try {

            const response = await fetch(
                `index.php?action=getHabitacionesPorCategoria&id_categoria=${tipoHabitacion.value}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`
            );

            const habitaciones = await response.json();

            numeroHabitacion.innerHTML =
                '<option value="">Seleccione una habitación</option>';

            if (habitaciones.error || habitaciones.length === 0) {

                numeroHabitacion.innerHTML =
                    '<option value="">No hay habitaciones disponibles</option>';

                return;
            }

            habitaciones.forEach((habitacion) => {

                numeroHabitacion.innerHTML += `
                    <option value="${habitacion.id}">
                        Habitación ${habitacion.num_habitacion}
                    </option>
                `;
            });

            numeroHabitacion.disabled = false;

            const habitacionPrevia = numeroHabitacion.dataset.habitacion;
            if (habitacionPrevia) {
                numeroHabitacion.value = habitacionPrevia;
            }

        } catch (error) {

            console.log(error);

            numeroHabitacion.innerHTML =
                '<option value="">Error al cargar habitaciones</option>';
        }
    });
});