<form id='form_modifica_ref' role='form'>
    <input type='hidden' id='id_referencia' name='ref_clave' id='lib_clave' />
    <div class='form-group'><label for='tipo'> Selecciona un tipo de referencia*</label>
        <select onchange='getValue()' class='form-control' id='tipo_referencia_modifica' required>
            <option value=''>Selecciona una opción...</option>
            <option value='L'>Libro</option>
            <option value='AR'>Artículo de revista</option>
            <option value='AP'>Artículo de periódico</option>
            <option value='SW'>Sitio Web</option>
        </select>
    </div>
    <div id='formulario_libro' style='display: none;'>
        <div class='form-group'>
            <label for='titulo'>Título del libro* </label>
            <input type='text' value=''class='form-control' name='titulo_libro_referencia_modifica' id='titulo_referencia_modifica' maxlength='50' minlength='5' required>
        </div>
        <div class='form-group'>
            <label for='autor'> Autor(res)* </label>
            <input type='text' value='' class='form-control' name='autores_referencia_modifica' id='autores_referencia_modifica' maxlength='50' minlength='7' required>
        </div>
        <div class='form-group'>
            <label for='anio'>Año* </label>
            <input type='number' value ='' name='anio_referencia_modifica' maxlength='4' minlength='4'  class='form-control' name='anio_referencia_modifica' id='anio_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='ciudad'>Ciudad* </label>
            <input type='text' value=''class='form-control' name='ciudad_referencia_modifica' id='ciudad_referencia_modifica' maxlength='15' minlength='4' required>
        </div>
        <div class='form-group'>
            <label for='editorial'>Editorial* </label>
            <input type='text' value='' class='form-control' name='editorial_referencia_modifica' id='editorial_referencia_modifica' maxlength='20' minlength='4' required>
        </div>
        <div class='form-group'>
            <label for='descripcion'> Descripción de la referencia </label>
            <textarea type='text' class='form-control'id='descripcion_libro_referencia_modifica'></textarea>
        </div>
    </div>
    <div id='formulario_articulo_revista' style='display: none;'>
        <div class='form-group'>
            <label for='titulo'>Título del artículo* </label>
            <input type='text' value='' class='form-control' name='titulo_artticulo_revista_referencia_modifica' id='titulo_artticulo_revista_referencia_modifica' maxlength='50' minlength='5' required>
        </div>
        <div class='form-group'>
            <label for='autor'> Autor(res)* </label>
            <input type='text' value='' class='form-control' name='autores_revista_referencia_modifica' id='autores_revista_referencia_modifica' maxlength='50' minlength='7' required>
        </div>
        <div class='form-group'>
            <label for='nombre_ciudad'>Nombre de la revista* </label>
            <input type='text' value='' class='form-control' name='nombre_revista_referencia_modifica' id='nombre_revista_referencia_modifica' maxlength='30' minlength='5' required>
        </div>
        <div class='form-group'>
            <label for='npaginas'>Página(as)* </label>
            <input type='text' value=''class='form-control' name='paginas_revista_referencia_modifica' id='paginas_revista_referencia_modifica' maxlength='20' minlength='1' required>
        </div>
        <div class='form-group'>
            <label for='anio'>Año* </label>
            <input type='number' value ='' name='anio_referencia_modifica' maxlength='4' minlength='4'  class='form-control' name='anio_revista_referencia_modifica' id='anio_revista_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='editorial'>Editorial* </label><input type='text' value='' class='form-control' maxlength='25' minlength='4' name='editorial_revista_referencia_modifica' id='editorial_revista_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='descripcion'> Descripción de la referencia </label>
            <textarea type='text' class='form-control' id='descripcion_revista_referencia_modifica'></textarea>
        </div>
    </div>
    <div id='formulario_articulo_periodico' style='display: none;'>
        <div class='form-group'>
            <label for='titulo_periodico'>Título del artículo* </label>
            <input type='text' value=''class='form-control' minlength='4' maxlength='50' name='titulo_articulo_periodico_referencia_modifica' id='titulo_articulo_periodico_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='autor'> Autor(res)* </label>
            <input type='text' value='' class='form-control' minlength='7' maxlength='50' name='autores_periodico_referencia_modifica' id='autores_periodico_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='titulo_periodico'>Título del periódico* </label>
            <input type='text' value=''class='form-control' minlength='2' maxlength='30' name='titulo_periodico_referencia_modifica' id='titulo_periodico_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='fecha'>Fecha* </label>
            <input type='date' value=''class='form-control' name='fecha_periodico_referencia_modifica' id='fecha_periodico_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='nPaginasPeriodico'>Página(as)* </label>
            <input type='text' value=''class='form-control' minlength='1' maxlength='15' name='paginas_periodico_referencia_modifica' id='paginas_priodico_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='descripcion'> Descripción de la referencia </label>
            <textarea type='text' class='form-control' id='descripcion_periodico_referencia_modifica'></textarea>
        </div>
    </div>
    <div id='formulario_sitio_web' style='display: none;'>
        <div class='form-group'>
            <label for='nombre_sitio'>Nombre del sitio web* </label>
            <input type='text' value='' class='form-control' minlength='5' maxlength='45' name='nombre_sitio_web_referencia_modifica' id='nombre_sitio_web_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='autor'> Autor(res)* </label>
            <input type='text' value='' class='form-control' minlength='7' maxlength='50' name='autores_sitio_referencia_modifica' id='autores_sitio_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='fecha'>Fecha* </label>
            <input type='date' value='' class='form-control' name='fecha_sitio_referencia_modifica' id='fecha_sitio_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='url_sitio'>URL* </label>
            <input type='text' placeholder='Ejemplo: http://ejemplo.com' value='' class='form-control' minlength='11' maxlength='290' name='url_sitio_referencia_modifica' id='url_sitio_referencia_modifica' required>
        </div>
        <div class='form-group'>
            <label for='descripcion'> Descripción de la referencia </label>
            <textarea type='text' class='form-control' id='descripcion_sitio_referencia_modifica'></textarea>
        </div>
    </div>
</form>