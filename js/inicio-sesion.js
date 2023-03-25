document.querySelector('.boton-inicio-sesion').addEventListener('click', (e) => {
  e.preventDefault();

  fetch('php/includes/iniciosesion.inc.php', {
    method: 'POST',
    body: new FormData(document.querySelector('.formulario'))
  })
    .then((respuesta) => respuesta.json())
    .then((datos) => {
      document.querySelectorAll('.notificaciones').forEach((e) => e.remove());

      switch (datos.tipo) {
        case 'url':
          location.href = datos.contenido;
          break;

        case 'mensaje':
          const mensaje = datos.contenido.mensaje;
          const ambito = datos.contenido.ambito;
          const notificacion = new Notificacion(mensaje, ambito);
          const notificaciones = document.querySelector(`.notificacion-${ambito}`);

          if (!notificaciones) {
            document.querySelector('.campos').after(notificacion.elemento);
            return;
          }

          notificaciones.appendChild(document.createElement('br'));
          notificaciones.appendChild(document.createTextNode(notificacion.mensaje));
          break;

        case 'array':
          datos.contenido.forEach((item) => {
            const notificacion = new Notificacion(item.mensaje, item.ambito);
            const notificaciones = document.querySelector(`.notificacion-${notificacion.ambito}`);

            if (!notificaciones) {
              document.querySelector('.campos').after(notificacion.elemento);
              return;
            }

            notificaciones.appendChild(document.createElement('br'));
            notificaciones.appendChild(document.createTextNode(notificacion.mensaje));
          });
          break;
      }
    });
});
