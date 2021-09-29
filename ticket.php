
<?php
date_default_timezone_set('America/Lima');
header("Access-Control-Allow-Origin:https://app.platcont.com");
require __DIR__ . '/vendor/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
include 'Fphp/funcglophp.php';
$accion = $_POST['accion'];

if (isset($accion) and !empty($accion) and $accion == 'print-ticket') {
    $data=$_POST['data'];
    $data_header=$data['header'];
    $data_config=$data['config'];
    $data_info=$data['info'];
    $data_otros=$data['otros'];
    $l_clie = $data['l_clie'];

    $s_capi = number_format(GrabNumSQL($data_header['s_impo']),2,'.',',');
    $s_inte = number_format(GrabNumSQL($data_header['s_inte']),2,'.',',');
    $s_mora = number_format(GrabNumSQL($data_header['s_mora']),2,'.',',');
    $s_desc = number_format(GrabNumSQL($data_header['s_desc']),2,'.',',');
    $s_tota = number_format(GrabNumSQL($data_header['s_tota']),2,'.',',');

    /*  $c_comp = $_POST['c_comp'];
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
        $s_tota = GrabNumSQL($_POST['s_tota']); */ 
    


    $nombre_impresora = $data_config['l_impr'];// "EPSON TM-T20II Receipt"; 

    //$nombre_impresora = "CutePDF Writer"; 
    $connector = new WindowsPrintConnector($nombre_impresora);
    $printer = new Printer($connector);




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
    $n_cuotas=$data_otros['n_cuoti'].'-'.$data_otros['n_cuotf'];
    if ($data_otros['n_cuoti']==$data_otros['n_cuotf']) {
        $n_cuotas=$data_otros['n_cuoti'];
    }



    $printer->text($data_info['l_orga'] . "\n");
    #La fecha también
    $printer->text('Fecha:'.date("Y-m-d H:i:s") . "\n");
    $printer->text('Nº:'.$data_header['n_seri'].' - '.$data_header['n_comp']. "\n");
    $printer->text("================================================" . "\n");
    $printer->text("Producto: Credito Consumo" . "\n");
    $printer->text("Cliente: ".ucwords(strtolower($l_clie))." DNI: ".$data_header['n_docu']. "\n");
    $printer->text("Fecha Vcmto: ".$data_otros['f_venc']."   Nº Cuota:".$n_cuotas."/".$data_otros['tn_cuot']. "\n");
    $printer->text("Fecha Prox. Vcmto: ".$data['f_vencpx'] . "\n");
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
    $printer->setJustification(Printer::JUSTIFY_CENTER);   
    $printer->text("JR.ANGARAES Nº 544 HUANCAYO" . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text('CJ-'.$data_info['l_caja'] . "\n");


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



    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text($data_info['l_orga'] . "\n");
    #La fecha también
    $printer->text('Fecha:'.date("Y-m-d H:i:s") . "\n");
    $printer->text('Nº:'.$data_header['n_seri'].' - '.$data_header['n_comp']. "\n");
    $printer->text("================================================" . "\n");
    $printer->text("Producto: Credito Consumo" . "\n");
    $printer->text("Cliente: ".ucwords(strtolower($l_clie))." DNI: ".$data_header['n_docu']. "\n");
    $printer->text("Fecha Vcmto: ".$data_otros['f_venc']."   Nº Cuota:".$n_cuotas."/".$data_otros['tn_cuot']. "\n");
    $printer->text("Fecha Prox. Vcmto: ".$data['f_vencpx'] . "\n");
    $printer->text("================================================" . "\n");


    /*Alinear a la izquierda para la cantidad y el nombre*/
    

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
        $printer->setJustification(Printer::JUSTIFY_CENTER);   
        $printer->text("JR.ANGARAES Nº 544 HUANCAYO" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text('CJ-'.$data_info['l_caja'] . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text('COPIA'. "\n");


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
