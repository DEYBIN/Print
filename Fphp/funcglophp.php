<?php

 // duplicates m$ excel's ceiling function

function ceiling($number, $significance = 1){
  return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}

// autocompleta los datos con ceros cuanto tu le detalles;
function AutoCompl($t,$l){
     $cadena="";
     $texto="";
     if (trim($t)!="") {
      $l=$l-strlen($t);
       for ($i=0; $i <= $l-1; $i++) { 
           $cadena= $cadena."0";
       }
       $texto=  $cadena.$t;
     }else{
      for ($i=0; $i <= $l-1; $i++) { 
           $cadena= $cadena."0";
            $texto=  $cadena;
       }
     }

        return $texto; 

}
// autocompleta los datos con ceros cuanto tu le detalles;
function AutoCompl_tk($t,$l,$tk){
     $cadena="";
     $texto="";
     if (trim($t)!="") {
      $l=$l-strlen($t);
       for ($i=0; $i <= $l-1; $i++) { 
           $cadena= $cadena.$tk;
       }
       $texto=  $cadena.$t;
     }else{
      for ($i=0; $i <= $l-1; $i++) { 
           $cadena= $cadena.$tk;
            $texto=  $cadena;
       }
     }

        return $texto; 

}
function FechToSQL($f){
 
  if ($f=="" or $f=="//" or $f=="  /  /    " ) {
   $f="01/01/1900";
  }
   
  //usamos la funcion explode y ponemos como cortador / 
  $partes= explode("/", $f); 

  //vemos si la fecha es correcta recuerden que aki la funcion es mes/dia/año 
  //pero en la caja de texto dia/mes/año para no complicar al usuario  

  if(checkdate ($partes[1],$partes[0],$partes[2]))   { 
    $date = date_create_from_format('d/m/Y', $f);
      $date= $date->format('Y-m-d');
      return $date;
  }else 
  { 
    $date = "1900-01-01";
    return $date;
  } 

}

function FechToPhp($f){
 
  if ($f=="1900-01-01" or $f=="//" or $f=="" or $f=="  /  /    " or $f=="1999-01-01" ) {
    $date="";
      return $date;
  }else {
    $date = date_create_from_format('Y-m-d', $f);
    $date= date_format($date,'d/m/Y');
      return $date;
  }
} 

//devuelve true si la fecha dada es domingo 
function whatdate($f){
 
  if ($f=="" or $f=="//" or $f=="  /  /    " ) {
   $f="01/01/1900";
  }
   
  //usamos la funcion explode y ponemos como cortador / 
  $partes= explode("/", $f); 

  //vemos si la fecha es correcta recuerden que aki la funcion es mes/dia/año 
  //pero en la caja de texto dia/mes/año para no complicar al usuario  

  if(checkdate ($partes[1],$partes[0],$partes[2]))   { 
    $date = date_create_from_format('d/m/Y', $f);
    $date= $date->format('Y-m-d');
   
    $domingo =date("D",strtotime($date)); 
    //echo $domingo;      
    //Comparamos si estamos en sabado o domingo, si es asi restamos una vuelta al for, para brincarnos el o los dias...  
    if ($domingo == "Sun") {  
      return true;  
    }else{  
      //Si no es sabado o domingo, y el for termina y nos muestra la nueva fecha  
     return false; 
    }  

  }else{ 
    
    return false;
  } 

}

function GrabNumSQL($v){
  if(is_numeric($v)) {
    
       
    } else {
       $v=0;
    }
    return $v;
}

function NumToPhp($v){
  $v1="";
  if(is_numeric($v)) {
      if ($v==0) {
            return $v1;
      }else{
        return number_format($v, 2, '.', '');
      }
       
    } else {
      return $v1;
    }
   
}

//devuelve los nombres de los meses
function nombremes($mes){
    setlocale(LC_TIME, 'spanish');
    $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
    return $nombre;
}
// valida si es un afecha
function validar_fecha_espanol($fecha){
  $valores = explode('/', $fecha);
  if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
    return true;
    }
  return false;
}

function sum_fech($f,$d){
  if ($f=="" or $f=="//" or $f=="  /  /    " ) {
     return "";
  }else{
   $partes= explode("/", $f); 
   if(checkdate ($partes[1],$partes[0],$partes[2]))   { 
      $date = date_create_from_format('d/m/Y', $f);
      $date= $date->format('Y/m/d');
       $nuevafecha= strtotime ( '+'.$d.' day' , strtotime ( $date ) ) ;

      return date("d/m/Y",$nuevafecha);
    }else{     
      return '';
    }  
  }
}

function res_fech($f,$d){
  if ($f=="" or $f=="//" or $f=="  /  /    " ) {
     return "";
  }else{
   $partes= explode("/", $f); 
   if(checkdate ($partes[1],$partes[0],$partes[2]))   { 
      $date = date_create_from_format('d/m/Y', $f);
      $date= $date->format('Y/m/d');
       $nuevafecha= strtotime ( '-'.$d.' day' , strtotime ( $date ) ) ;

      return date("d/m/Y",$nuevafecha);
    }else{     
      return '';
    }  
  }
}
//hallar diferencia entre dos fechas debuelve los dias
function divf_fech($fv,$fp){
  
  $date1 =$fv;
  $date2 = $fp;
  $partes= explode("/", $date1);
  if(checkdate ($partes[1],$partes[0],$partes[2]))   { 
      $date1 = new DateTime($partes[0].'-'.$partes[1].'-'.$partes[2]);
    }
     $partes= explode("/", $date2);
    if(checkdate ($partes[1],$partes[0],$partes[2]))   {       
      $date2=new DateTime($partes[0].'-'.$partes[1].'-'.$partes[2]);
    }
  

  $diff = $date1->diff($date2);
  // will output 2 days
  $sig=$diff->invert;
  $day=$diff->days;
  if ($sig=='') {
    $day=-1*$day;
  }
  return $day;
     /* fuente: https://programacion.net/articulo/calcular_la_diferencia_entre_dos_fechas_con_php_1566
     Si imprimes el objeto $diff verás lo siguiente.
     DateInterval Object(
        [y] => 0 // year
        [m] => 0 // month
        [d] => 2 // days
        [h] => 0 // hours
        [i] => 0 // minutes
        [s] => 0 // seconds
        [invert] => 0 // positive or negative 
        [days] => 2 // total no of days
      )*/

}


// genera asientos contables de ventas
function Gen_Vent($id_vouch,$c_cuen,$c_cuec,$c_cuep,$s_impo,$s_exon,$s_igv,$s_tota,$s_perc,$s_detr,$K_tipp,$k_mone,$s_tipc,$l_glos0){
          $dt_array=array();
          $b=0;
          $i=0;
          //asientos Ventas
        

           $b=1;
          //Cuenta de Ingreso
          $dt_array[$i]["gid_vouch"]=$id_vouch;
          $dt_array[$i]["gn_item"]=AutoCompl($b,4);
          $dt_array[$i]["gc_cuen"]=$c_cuen;
          $dt_array[$i]["gs_debe"]=0;
          $dt_array[$i]["gs_habe"]=($s_impo+ $s_exon);
          $dt_array[$i]["gk_mone"]=$k_mone;
          $dt_array[$i]["gs_tipc"]=$s_tipc;
          $dt_array[$i]["gl_glos0"]=$l_glos0;                   
          $i+=1;         
          $b+=1;
          if ($s_igv!=0) {
            //Cuenta de IGV
            $c_cueigv=$_SESSION['_c_cuenigv'];

            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cueigv;
            $dt_array[$i]["gs_debe"]=0;
            $dt_array[$i]["gs_habe"]=$s_igv;
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;
          }

          if ($s_perc!=0) {
            //Cuenta percepciones
            $c_cueperc=$_SESSION['_c_cuenperc'];

            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cueperc;
            $dt_array[$i]["gs_debe"]=0;
            $dt_array[$i]["gs_habe"]=$s_perc;
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;

          }

          //Cuenta compromiso 
          $dt_array[$i]["gid_vouch"]=$id_vouch;
          $dt_array[$i]["gn_item"]=AutoCompl($b,4);
          $dt_array[$i]["gc_cuen"]=$c_cuec;
          $dt_array[$i]["gs_debe"]=($s_tota+$s_perc);
          $dt_array[$i]["gs_habe"]=0;
          $dt_array[$i]["gk_mone"]=$k_mone;
          $dt_array[$i]["gs_tipc"]=$s_tipc;
          $dt_array[$i]["gl_glos0"]=$l_glos0;
          $i+=1;         
          $b+=1;                     
              
          if ( $K_tipp!=3) {
            //Cuenta compromiso cancelado
            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cuec;
            $dt_array[$i]["gs_debe"]=0;
            $dt_array[$i]["gs_habe"]=($s_tota+$s_perc);
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;

            if ($s_detr!=0) {
              //Cuenta detraccion
              $c_cuedetrv=$_SESSION['_c_cuendetv'];
              $dt_array[$i]["gid_vouch"]=$id_vouch;
              $dt_array[$i]["gn_item"]=AutoCompl($b,4);
              $dt_array[$i]["gc_cuen"]=$c_cuedetrv;
              $dt_array[$i]["gs_debe"]=$s_detr;
              $dt_array[$i]["gs_habe"]=0;
              $dt_array[$i]["gk_mone"]=$k_mone;
              $dt_array[$i]["gs_tipc"]=$s_tipc;
              $dt_array[$i]["gl_glos0"]=$l_glos0;
              $i+=1;
              $b+=1;
            } 

            if ($K_tipp==1 or $K_tipp==2 ) {
              //asiento cancelacion de compromiso con efectivo o banco              
              $dt_array[$i]["gid_vouch"]=$id_vouch;
              $dt_array[$i]["gn_item"]=AutoCompl($b,4);
              $dt_array[$i]["gc_cuen"]=$c_cuep;
              $dt_array[$i]["gs_debe"]=($s_tota+$s_perc-$s_detr);
              $dt_array[$i]["gs_habe"]=0;
              $dt_array[$i]["gk_mone"]=$k_mone;
              $dt_array[$i]["gs_tipc"]=$s_tipc;
              $dt_array[$i]["gl_glos0"]=$l_glos0;
              $i+=1;
              $b+=1;
            }
          }

           return $dt_array;
}
//genera asientos contables de compras
function Gen_Comp($id_vouch,$c_cuen,$c_cuec,$c_cuep,$s_impo,$s_exon,$s_igv,$s_tota,$s_perc,$s_detr,$K_tipp,$k_mone,$s_tipc,$l_glos0){
          $dt_array=array();
          $b=0;
          $i=0;
          //asientos Compras
           $b=1;
          //Cuenta de Ingreso
           if (is_array($c_cuen)) {
              $lent=count($c_cuen);
              for ($e=0; $lent>$e ; $e++) {
                $vs_impo=$c_cuen[$e]["s_bimp"];
                $vs_exon=$c_cuen[$e]["s_exon"];
                $vc_cuen=trim($c_cuen[$e]["cta"]);
                $dt_array[$i]["gid_vouch"]=$id_vouch;
                $dt_array[$i]["gn_item"]=AutoCompl($b,4);
                $dt_array[$i]["gc_cuen"]=$vc_cuen;
                $dt_array[$i]["gs_debe"]=($vs_impo+ $vs_exon);
                $dt_array[$i]["gs_habe"]=0;
                $dt_array[$i]["gk_mone"]=$k_mone;
                $dt_array[$i]["gs_tipc"]=$s_tipc;
                $dt_array[$i]["gl_glos0"]=$l_glos0;                   
                $i+=1;         
                $b+=1;
              }
            }else{
              $dt_array[$i]["gid_vouch"]=$id_vouch;
              $dt_array[$i]["gn_item"]=AutoCompl($b,4);
              $dt_array[$i]["gc_cuen"]=$c_cuen;
              $dt_array[$i]["gs_debe"]=($s_impo+ $s_exon);
              $dt_array[$i]["gs_habe"]=0;
              $dt_array[$i]["gk_mone"]=$k_mone;
              $dt_array[$i]["gs_tipc"]=$s_tipc;
              $dt_array[$i]["gl_glos0"]=$l_glos0;                   
              $i+=1;         
              $b+=1;
            }
          
          if ($s_igv!=0) {
            //Cuenta de IGV
            $c_cueigv=$_SESSION['_c_cuenigv'];

            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cueigv;
            $dt_array[$i]["gs_debe"]=$s_igv;
            $dt_array[$i]["gs_habe"]=0;
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;
          }

          if ($s_perc!=0) {
            //Cuenta percepciones
            $c_cueperc=$_SESSION['_c_cuenperc'];

            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cueperc;
            $dt_array[$i]["gs_debe"]=$s_perc;
            $dt_array[$i]["gs_habe"]=0;
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;

          }

          //Cuenta compromiso 
          $dt_array[$i]["gid_vouch"]=$id_vouch;
          $dt_array[$i]["gn_item"]=AutoCompl($b,4);
          $dt_array[$i]["gc_cuen"]=$c_cuec;
          $dt_array[$i]["gs_debe"]=0;
          $dt_array[$i]["gs_habe"]=($s_tota+$s_perc);
          $dt_array[$i]["gk_mone"]=$k_mone;
          $dt_array[$i]["gs_tipc"]=$s_tipc;
          $dt_array[$i]["gl_glos0"]=$l_glos0;
          $i+=1;         
          $b+=1;                     
              
          if ( $K_tipp!=3) {
            //Cuenta compromiso cancelado
            $dt_array[$i]["gid_vouch"]=$id_vouch;
            $dt_array[$i]["gn_item"]=AutoCompl($b,4);
            $dt_array[$i]["gc_cuen"]=$c_cuec;
            $dt_array[$i]["gs_debe"]=($s_tota+$s_perc);
            $dt_array[$i]["gs_habe"]=0;
            $dt_array[$i]["gk_mone"]=$k_mone;
            $dt_array[$i]["gs_tipc"]=$s_tipc;
            $dt_array[$i]["gl_glos0"]=$l_glos0;
            $i+=1;         
            $b+=1;

            if ($s_detr!=0) {
              //Cuenta detraccion
              $c_cuedetrc=$_SESSION['_c_cuendetc'];
              $dt_array[$i]["gid_vouch"]=$id_vouch;
              $dt_array[$i]["gn_item"]=AutoCompl($b,4);
              $dt_array[$i]["gc_cuen"]=$c_cuedetrc;
              $dt_array[$i]["gs_debe"]=0;
              $dt_array[$i]["gs_habe"]=$s_detr;
              $dt_array[$i]["gk_mone"]=$k_mone;
              $dt_array[$i]["gs_tipc"]=$s_tipc;
              $dt_array[$i]["gl_glos0"]=$l_glos0;
              $i+=1;
              $b+=1;
            } 

            if ($K_tipp==1 or $K_tipp==2 ) {
              //asiento cancelacion de compromiso con efectivo o banco              
              $dt_array[$i]["gid_vouch"]=$id_vouch;
              $dt_array[$i]["gn_item"]=AutoCompl($b,4);
              $dt_array[$i]["gc_cuen"]=$c_cuep;
              $dt_array[$i]["gs_debe"]=0;
              $dt_array[$i]["gs_habe"]=($s_tota+$s_perc-$s_detr);
              $dt_array[$i]["gk_mone"]=$k_mone;
              $dt_array[$i]["gs_tipc"]=$s_tipc;
              $dt_array[$i]["gl_glos0"]=$l_glos0;
              $i+=1;
              $b+=1;
            }
          }

           return $dt_array;
}
// genera asientos contable de destino
function Gen_AsiDest($id_vouch,$s_debe,$s_habe,$itemv,$datos,$k_mone,$s_tipc,$l_glos){
  $dt_array=array();
  $b=0;
  $i=0;
  foreach ($datos as $k => $v) {                 
    $b+=1;
    $dt_array[$i]['gid_vouch']=$id_vouch;
    $dt_array[$i]['gn_item']=AutoCompl($b,4);
    $dt_array[$i]['gitemvou']=$itemv;
    $dt_array[$i]['gc_cuen']=$v['c_cu01'];
    $dt_array[$i]['gs_debe']=$s_debe*($v['s_porc']/100);
    $dt_array[$i]['gs_habe']=$s_habe*($v['s_porc']/100);
    $dt_array[$i]['gk_mone']=$k_mone;
    $dt_array[$i]['gs_tipc']=$s_tipc;
    $dt_array[$i]['gl_glos']=$l_glos;
    
    $b+=1;
    $i+=1;

    $dt_array[$i]['gid_vouch']=$id_vouch;
    $dt_array[$i]['gn_item']=AutoCompl($b,4);
    $dt_array[$i]['gitemvou']=$itemv;
    $dt_array[$i]['gc_cuen']=$v['c_cu02'];
    $dt_array[$i]['gs_debe']=$s_habe*($v['s_porc']/100);
    $dt_array[$i]['gs_habe']=$s_debe*($v['s_porc']/100);
    $dt_array[$i]['gk_mone']=$k_mone;
    $dt_array[$i]['gs_tipc']=$s_tipc;
    $dt_array[$i]['gl_glos']=$l_glos;

                     
  }         
  
  return $dt_array;
}
//trae las cuentas de destino de una cuenta dada
function CuentDests($C){   
 
    $usu      = $_SESSION['_usu'];
    $cont     = $_SESSION['_cont'];
    $linea    = $_SESSION['_linea'];
    $database = $_SESSION['_database'];
    $info     = array("Database" => $database, "UID" => $usu, "PWD" => $cont, 'ReturnDatesAsStrings' => true, "CharacterSet" => "UTF-8");
    $cnn      = sqlsrv_connect($linea, $info);
    $query    = sqlsrv_query($cnn,"select * from Destino where  c_ano='$_SESSION[_c_ano]' and  c_cupr='".$C."'");
   $I        = 0;
    $C        = array();
    while ($datos = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {

        $C[$I] = $datos;
        $I++;
    }

        return $C;
}
// genera asientos contable de destino en bloque
function Gen_AsiDest_bloq($dt_voucherdet){
 $lent1=count($dt_voucherdet);
  $dt_asidest= array();
    if ($lent1!=0) {
        $a=0;
        $j_t=0;       
        for ($i=0; $lent1>$i ; $i++) {
            $lent2=count($dt_voucherdet[$i]);
            if ($lent2!=0) {
               for ($j=0; $lent2>$j;$j++) {
                  $dt_dest=CuentDests($dt_voucherdet[$i][$j]["gc_cuen"]);
                   if (count($dt_dest)!=0) {                     
                      $dt_asidest[$a]=Gen_AsiDest($dt_voucherdet[$i][$j]["gid_vouch"],$dt_voucherdet[$i][$j]["gs_debe"],$dt_voucherdet[$i][$j]["gs_habe"],$dt_voucherdet[$i][$j]["gn_item"],$dt_dest,$dt_voucherdet[$i][$j]["gk_mone"],$dt_voucherdet[$i][$j]["gs_tipc"],$dt_voucherdet[$i][$j]["gl_glos0"]);
                      $a+=1;
                    }
               }
            }            
        }
      }
          
  
  return $dt_asidest;
}


//saca tipo de cambio sunat
function Tipo_cambio($mes,$ano){
  $Page = file_get_contents("http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes=$mes&anho=$ano");
  $patron2='/(?:<td.*>+\s+\t?(.*))(?:<\/td>)?/';
  $patron3='/<strong>(.*)<\/strong>/';
  //$numero = cal_days_in_month(CAL_GREGORIAN, 8, 2017);
  $output = preg_match_all($patron2, $Page, $matches, PREG_SET_ORDER);        
  $lent= count($matches);
  $e=0;       
  $D= array();

  for ($i=7; $i<$lent ; $i++) {
    if ( strlen($matches[$i][0])==73) {
      break;
    }
    $v1=trim($matches[$i][1]);
          $output1 = preg_match_all($patron3, $v1, $matches1, PREG_SET_ORDER);
          $v1=AutoCompl($matches1[0][1],2);
            $i+=1;
            $v2=trim($matches[$i][1]);         
            $i+=1;
            $v3=trim($matches[$i][1]); 
            $D[$e]=$v1.'|'.$v2.'|'.$v3;
            $e+=1;
  }
  return $D;
}

//halla la tasa real de un credito metodo frances
function TIR($c,$n,$p,$t){
  //$f=flujo de caja ---> cuotas 
  //$c=capital invertido ----> prestamo
  //$n=numero de flujos de caja --->numero de cuotas
  //$p=periodo de pago  --->cada cuantos dias se paga la cuota Men-Sem-Qui-Dia
  //$t=porcentaje de la tasa segun su tipo Men-Sem-Qui-Dia
  if ($p==7) {
   $m=(7.5*$n)/30;
  }else{
    $m=($p*$n)/30;
  }
  
  $f=((($c*$t)*$m)+$c)/$n;
  $ts=pow(1+$t, 1/30)-1;
  $ts=pow(1+$ts,$p)-1 ;
  $vpn=7;
  $tr=0;
  $val=1;
  while ($vpn>=0) {
    $val=0;
    $val=0;
    for ($i=1; $i<=$n ; $i++) { 
      $val+=($f/((1+$ts)**$i));
    }
    
    $vpn=-$c+$val;
    $tr=$ts;
    $ts+=.000001;
  }
  $nt=pow(1+$tr, 1/$p)-1;
  $tr=pow(1+$nt, 30)-1;
  return $tr;
}