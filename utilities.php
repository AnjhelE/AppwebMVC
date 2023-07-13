<?php
/**
 * Imprime la vista.
 * 
 * @param ?string $viewName Nombre de la vista, se usa por defecto el nombre del controlador actual
 * @param ?string $viewPath Ruta de vista, se usa por defecto la misma ruta que el controlador actual
 * @return void
 **/
function renderView(?string $viewName = null, ?string $viewPath = null) : void
{
    $viewName ??= $GLOBALS['controllerName'];
    $viewPath ??= $GLOBALS['controllerPath'];

    foreach ($GLOBALS as $key => $value) $$key = $value;
    
    $_layout ??= "Principal";

    if (!is_file("Views/".$viewPath.$viewName.".php")) {
        http_response_code(404);
        die("[Error] No se encontro la vista en <b>Views/".$viewPath.$viewName.".php</b>");
    }
    if (!is_file("Views/_Plantillas/".$_layout.".php")) {
        http_response_code(404);
        die("[Error] No se encontro la plantilla en <b>Views/_Plantillas/".$_layout.".php</b>");
    }

    try {
        ob_start('saveViewBuffer');
        require "Views/".$viewPath.$viewName.".php";
        ob_end_clean();
    } catch (\Throwable $th) {
        ob_end_clean();
        throw $th;
    }

    require "Views/_Plantillas/".$_layout.".php";
}

/**
 * Valida si el usuario esta autenticado, de lo contrario se redirecciona al login
 */
function necesitaAutenticacion() : void {
    if (!isset($_SESSION['usuario'])) {
        header('location:'.LOCAL_DIR.'login');
        exit();
    }
}

/** 
 * Almacena el buffer de la vista que sera impresa
 * 
 * @param string $buffer Buffer a almacenar
 * @return string Cadena a imprimir luego de almacenar el buffer (Cadena vacia)
*/
function saveViewBuffer(string $buffer)
{
    $GLOBALS['view'] .= $buffer;
    return "";
}
?>