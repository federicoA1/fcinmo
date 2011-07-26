<?php
include_once("classes/Utiles.php");

session_start(); 

class Main {

   public static $salir = "Salir";
    
   function wrapper($content,$title,$links=1)  {
        set_file("all", "wrapper.html");
        if ($links){
            //tengo más de una sección?
            
            s("link",Main::getLinks(1)); 
            pp("links");
        }
        else{
            s("links","");
        }
        set_var("contenido", $content);
        return parse("all");
    }
    
    function getLinks($idCategoria){
        $idUser =  $_SESSION["id_user"];
        $nombreCategoria = Main::getField("sc_categorias","nombre",$idCategoria);
        $resultado = '<div class="seccion"><h1>'.$nombreCategoria.'</h1>';
        $sql ="select * 
                from sc_secciones as s LEFT JOIN sc_privilegios as p ON  s.id = p.id_seccion
                where id_usuario = '$idUser' and id_categoria = '$idCategoria'";
        $rs = mysql_query($sql);
        echo mysql_error();
        $resultado.="<div class='bordeMenu'>";
        
        
        $seccionActual =  $_SESSION["seccionActual"];
      
        while ($filann = mfa($rs)){
            if ($filann["id_seccion"] == $seccionActual){
                $activee = ' class="activo" ';
            }else{
                $activee = "";
            }    
            $resultado .= "<a href='$filann[link]'  $activee >$filann[nombre]</a>";
        }
      
        //se agrega el salir y el div del contenedor del menu
        if ($idCategoria == 1)
            $resultado .= "<a href='index.php?act=logout' class='red'>".self::$salir."</a> ";      
        
        $resultado .= "</div>";
        
        return $resultado."</div>";
    }
    
   

    function selectHelper($nombre_select,$tabla,$campo_nombre,$campo_id,$seleccionado = ""){
        $rs =mysql_query("select $campo_nombre,$campo_id FROM $tabla order by $campo_nombre");
        
        $returnTXT = "<select name='$nombre_select' id='$nombre_select'>";
        echo mysql_error();
        $returnTXT .='<option value=""></option>';
        while($fila = mysql_fetch_assoc($rs)){
            $selected= "";
            if ($seleccionado == $fila[$campo_id]){
                $selected = 'selected="selected"';
            }
            
            $returnTXT = $returnTXT .'<option value="'.$fila[$campo_id].'" '.$selected.' >'.$fila[$campo_nombre].'</option>';
        }
        $returnTXT .="</select>";
        return $returnTXT;
    }
    
     function selectHelperAuto($nombre_select,$inicio,$fin,$seleccionado = "",$extra=""){
        $i=$inicio;
        $returnTXT = "<select name='$nombre_select' id='$nombre_select' $extra >";
        
        $returnTXT .='<option value="'.$inicio.'" >'.$inicio.'</option>';
        while($i < $fin){
            $i++;
            $selected= "";
            if ($seleccionado == $i){
                $selected = 'selected="selected"';
            }
            $returnTXT = $returnTXT .'<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        $returnTXT .="</select>";
        return $returnTXT;
    }
    
     function selectBusqueda($nombre_select,$seleccionado = "",$extra=""){
        $valores=array("Es","Contiene","Comienza con","Termina en");
        
        $returnTXT = "<select name='$nombre_select' id='$nombre_select' $extra >";
        $i = 0;
        while($i < 5){
            
            $selected= "";
            if ($seleccionado == $i){
                $selected = 'selected="selected"';
            }
            $returnTXT = $returnTXT .'<option value="'.$i.'" '.$selected.' >'.$valores[$i].'</option>';
            $i++;
        }
        $returnTXT .="</select>";
        return $returnTXT;
    }
    
    function selectHelperAutoFormat($nombre_select,$inicio,$fin,$incremento,$formato,$seleccionado = ""){
        $i=$inicio;
        $returnTXT = "<select name='$nombre_select' id='$nombre_select'>";
        
        $returnTXT .='<option value="'.$inicio.'" >'.$inicio.$formato.'</option>';
        while($i < $fin){
            $i = $i+$incremento;
            $selected= "";
            if ($seleccionado == $i){
                $selected = 'selected="selected"';
            }
            $returnTXT = $returnTXT .'<option value="'.$i.'" '.$selected.'>'.$i.$formato.'</option>';
        }
        $returnTXT .="</select>";
        return $returnTXT;
    }
    

    
    function getConfig($key){
        $rs = mysql_query("select * from _config where clave = '$key'");
        $fila = mysql_fetch_assoc($rs);
        if ($fila){
            return $fila["valor"];
        }else
            return "NOT_DEFINED:$key";
    }
    
    function cargarTabla($tabla){
        $rs = mysql_query("select * from $tabla");
        while($fila = mysql_fetch_assoc($rs)){
            $arr[$fila[id]]=$fila;
        }
        return $arr;
    }
    
    function getField($tabla,$campo,$id_primaria){
        $fila = mysql_fetch_assoc(sql("select $campo FROM $tabla where id='$id_primaria'"));
        return $fila[$campo];
    }
    
    function getRow($tabla,$nombrePK ,$valorPK){
        return mfa(sql("select * from $tabla where $nombrePK = '$valorPK'"));
    }
    
    
    function obtenerMinimo($array){
        $res = 99999;
        foreach($array as $ele){
            if ($ele < $res && $ele > 0){
                $res = $ele;
            }
        }
        return $res;
        
    }
    
    function enviarHTMLMail($from,$to,$titulo,$mensaje){
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'To: '.$to.'' . "\r\n";
        $headers .= 'From: '.$from.'' . "\r\n";
        mail($to, $titulo, $mensaje, $headers);
    }
    
    
    function selectHelperCondExtra($nombre_select,$tabla,$campo_nombre,$campo_id,$condicion,$onchange,$seleccionado = "",$textoVacio='',$class=''){
        $rs =mysql_query("select $campo_nombre,$campo_id FROM $tabla $condicion order by $campo_nombre");
        $returnTXT = "<select name='$nombre_select' id='$nombre_select' onchange='$onchange' $class>";
        echo mysql_error();
        $returnTXT = $returnTXT .'<option value="">'.$textoVacio.'</option>';
        while($fila = mysql_fetch_assoc($rs)){
            if ($seleccionado && ($seleccionado == $fila[$campo_id])){
                $stxt = 'selected="selected"';
            }else{
                $stxt = '';
            }
                
            $returnTXT = $returnTXT .'<option value="'.$fila[$campo_id].'" '.$stxt.' >'.$fila[$campo_nombre].'</option>';
        }
        $returnTXT .="</select>";
        return $returnTXT;
    }
    
    function mensajeW($texto,$clase){
        return '<div class="'.$clase.'">'.$texto."</div>";
    }
    
    
 

 
}//end class
?>
