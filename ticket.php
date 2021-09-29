
<?php
 header("Access-Control-Allow-Origin:http://platcont.com");
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
include 'Fphp/funcglophp.php';
$accion = $_POST['accion'];

if (isset($accion) and !empty($accion) and $accion == 'print-ticket') {
    $c_comp = $_POST['c_comp'];
    $n_seri = $_POST['n_seri'];
    $n_comp = $_POST['n_comp'];
    $l_orga = $_POST['l_orga'];
    $n_docu = $_POST['n_docu'];
    $l_clie = $_POST['l_clie'];
    $f_venc = $_POST['f_venc'];
    $f_vencpx = $_POST['f_vencpx'];
    $n_cuoti = $_POST['n_cuoti'];
    $n_cuotf = $_POST['n_cuotf'];
    $vn_cuot = $_POST['vn_cuot'];//total de las cuotas
    $id_caja = $_POST['id_caja']; 
    $s_capi = GrabNumSQL($_POST['s_capi']);
    $s_inte = GrabNumSQL($_POST['s_inte']);
    $s_mora = GrabNumSQL($_POST['s_mora']);
    $s_desc = GrabNumSQL($_POST['s_desc']);
    $s_tota = GrabNumSQL($_POST['s_tota']);  
    

/*
    Este ejemplo imprime un
    ticket de venta desde una impresora térmica
*/


/*
    Una pequeña clase para
    trabajar mejor con
    los productos
    Nota: esta clase no es requerida, puedes
    imprimir usando puro texto de la forma
    que tú quieras
*/
class Producto{

    public function __construct($nombre, $precio, $cantidad){
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
    }
}

/*
    Vamos a simular algunos productos. Estos
    podemos recuperarlos desde $_POST o desde
    cualquier entrada de datos. Yo los declararé
    aquí mismo
*/

$productos = array(
        new Producto("Papas fritas", 10, 1),
        new Producto("Pringles", 22, 2),
        /*
            El nombre del siguiente producto es largo
            para comprobar que la librería
            bajará el texto por nosotros en caso de
            que sea muy largo
        */
        new Producto("Galletas saladas con un sabor muy salado y un precio excelente", 10, 1.5),
    );

/*
    Aquí, en lugar de "POS-58" (que es el nombre de mi impresora)
    escribe el nombre de la tuya. Recuerda que debes compartirla
    desde el panel de control
*/

$nombre_impresora = "HKA80"; 

//$nombre_impresora = "CutePDF Writer"; 
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*
    Vamos a imprimir un logotipo
    opcional. Recuerda que esto
    no funcionará en todas las
    impresoras

    Pequeña nota: Es recomendable que la imagen no sea
    transparente (aunque sea png hay que quitar el canal alfa)
    y que tenga una resolución baja. En mi caso
    la imagen que uso es de 250 x 250
*/

# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
    Intentaremos cargar e imprimir
    el logo
*/
//try{
//    $logo = EscposImage::load("Platcont.jpg", false);
//    $printer->bitImage($logo);
//}catch(Exception $e){/*No hacemos nada si hay error*/}

/*
    Ahora vamos a imprimir un encabezado
*/
$n_cuotas=$n_cuoti.'-'.$n_cuotf;
if ($n_cuoti==$n_cuotf) {
    $n_cuotas=$n_cuoti;
}

//$n_cuoti
//$n_cuotf

$printer->text($l_orga . "\n");
//$printer->text("FECAH : 01/01/2018" . "\n");
#La fecha también
$printer->text('Fecha:'.date("Y-m-d H:i:s") . "\n");
$printer->text('Nº:'.$n_seri.' - '.$n_comp. "\n");
$printer->text("================================================" . "\n");
$printer->text("Producto: Credito Consumo" . "\n");
$printer->text("Cliente: ".ucwords(strtolower($l_clie))." DNI: $n_docu" . "\n");
$printer->text("Fecha Vcmto: $f_venc   Nº Cuota:$n_cuotas/$vn_cuot" . "\n");
$printer->text("Fecha Prox. Vcmto: $f_vencpx" . "\n");
$printer->text("================================================" . "\n");

/*Alinear a la izquierda para la cantidad y el nombre*/
    //$printer->setJustification(Printer::JUSTIFY_LEFT);    

    $printer->setJustification(Printer::JUSTIFY_RIGHT);

    $printer->text('Pago Principal' . "   " . ':     S/.   ' .AutoCompl_tk($s_capi,15,'*'). "\n");    
    $printer->text('Pago Interes' . "   " . ':     S/.   ' .AutoCompl_tk($s_inte,15,'*'). "\n");
    $printer->text('Interes Moratorio' . "   " . ':     S/.   ' .AutoCompl_tk($s_mora,15,'*'). "\n");
    $printer->text('Interes Descuento' . "   " . ':     S/.   ' .AutoCompl_tk($s_desc,15,'*'). "\n");

    $printer->setJustification(Printer::JUSTIFY_CENTER);   
    $printer->text("------------------------------------------------" . "\n");

    /*Y a la derecha para el importe*/
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text('Total a Pagar' . "   " . ':     S/.   ' .AutoCompl_tk(ceiling($s_tota,0.1),15,'*') . "\n");

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("================================================" . "\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);   
    $printer->text("¡MUCHAS GRACIAS POR SU VISITA Y EL PAGO PUNTUAL DE SUS CUOTAS!" . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text('CJ-'.$id_caja . "\n");

//$printer->text("--------\n");
//$printer->text("TOTAL: $". $total ."\n");


/*
    Podemos poner también un pie de página
*/
//$printer->text("Muchas gracias por su compra\nparzibyte.me");



/*Alimentamos el papel 3 veces*/
$printer->feed(3);

/*
    Cortamos el papel. Si nuestra impresora
    no tiene soporte para ello, no generará
    ningún error
*/
$printer->cut();

/*
    Por medio de la impresora mandamos un pulso.
    Esto es útil cuando la tenemos conectada
    por ejemplo a un cajón
*/
$printer->pulse();

/*
    Para imprimir realmente, tenemos que "cerrar"
    la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();

}
?>