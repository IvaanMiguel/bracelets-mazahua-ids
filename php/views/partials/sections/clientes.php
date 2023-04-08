<link rel='stylesheet' href='css/sections/clientes.css'>
<div class='clientes'>
  <tab-secciones>
    <button class='boton boton-fondo-texto txt-fondo-alternativo' is='md-boton' slot='boton-administrar' data-evento='cargarclientes'>
      <md-icono data-icono='settings' data-opsz='24'></md-icono>
      <span class='titulo-mediano'>Administrar clientes</span>
    </button>
    <button class='boton boton-fondo-texto txt-fondo-alternativo' is='md-boton' slot='boton-agregar' data-evento='agregarcliente'>
      <md-icono data-icono='add' data-opsz='24'></md-icono>
      <span class='titulo-mediano'>Agregar cliente nuevo</span>
    </button>

    <div data-id='contenido-administrar'>
      <?php include_once 'customers/administrar-clientes.php' ?>
    </div>
    <div data-id='contenido-agregar'>
      <?php include_once 'customers/agregar-cliente.php' ?>
    </div>
  </tab-secciones>
</div>
