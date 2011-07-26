<?php
include_once("classes/Main.php");
include_once("includes/connect.php");
class Carrito extends Main{
   
    function agregar($arr){
        if ($arr[id]){
            $_SESSION["items"][$arr["id"]] += $arr["cantidad"];    
        }
        
        return Carrito::mostrar();
    }
    function cancelar(){
        $_SESSION["items"] = array();
        $_SESSION["items"] = false;
        header("location:index.php");
    }

    function mostrar($mensaje = ''){    
        set_file("content","carrito/listar.html");
        if ($mensaje){
            s("mensaje",Main::divW("$mensaje","info"));
        }else{
            s("mensaje","")
        }
        if ($_SESSION["items"] && sizeof($_SESSION["items"])){
            //print_r($_SESSION);
            $where = Carrito::construirWhere($_SESSION["items"]);
            $sql = "select * from productos where $where";
            $rs = sql($sql);
            $totalGeneral =0;
            while($fila = mfa($rs)){
                s("producto",$fila[nombre]);
                s("precioUnitario",$fila[precio]);
                $cantidad = $_SESSION[items][$fila[id]];
                s("cantidad",$cantidad);
                $total = $fila["precio"] * $cantidad;
                $totalGeneral += $total;
                s("total",$total);
                pp("productos");
            }
            
            s("url",$_SERVER['HTTP_REFERER']);
            s("totalGeneral",$totalGeneral);
            parse("noHay");
        }else{
            s("noHay",Main::divW("No hay productos en su carrito","info"));
        }
        
        return Main::wrapper(pp("content"),$nombreCategoria,0);
    }
    
    function construirWhere($arr){
        while (list($key, $val) = each($arr)) {
            $retorno .= " id = '$key' OR ";
        }
        return substr($retorno,0,-3);
    }
    
    function confirmar($arr){
       
        set_file("content","carrito/orden.html");
        if ($_SESSION["items"] && sizeof($_SESSION["items"])){
            
            $where = Carrito::construirWhere($_SESSION["items"]);
            $sql = "select * from productos where $where";
            $rs = sql($sql);
            $totalGeneral =0;
            
            s("nombre",$arr[nombre]);
            s("telefono",$arr[telefono]);
            s("email",$arr[email]);
            s("mensaje",$arr[mensaje]);
            
            while($fila = mfa($rs)){
                s("producto",$fila[nombre]);
                s("precioUnitario",$fila[precio]);
                $cantidad = $_SESSION[items][$fila[id]];
                s("cantidad",$cantidad);
                $total = $fila["precio"] * $cantidad;
                $totalGeneral += $total;
                s("total",$total);
                pp("productos");
            }
            
            s("url",$_SERVER['HTTP_REFERER']);
            s("totalGeneral",$totalGeneral);
            $contenido = pp("content");
            $from = "formularioVenta@vinotecasantafe.com.ar";
            $to = "contacto@vinotecasantafe.com,marialerub@gmail.com";
            $titulo = "Formulario de consulta";
            Main::enviarHTMLMail($from,$to,$titulo,$contenido);
                //MENSAJE
                set_file("content","mensaje.html"); 
                s("titulo","Muchas Gracias");
                s("mensaje","Nos contactaremos con usted a la brevedad");
                return Main::wrapper(pp("content"),"Muchas Gracias",0); 
        }else{
            return Carrito::mostrar("Error en el texto de verificación");
            
        }
        
        
        
    }
    
 
} 
    
?>
