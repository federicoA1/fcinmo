<?php
include_once("classes/Utiles.php");
session_start(); 

class Main {
    function wrapper($content,$title,$especialidad,$metaKey='',$metaDesc='')  {
       
       $especialidades = Main::getEspecialidades($especialidad); 
      
       set_file("all", "wrapper.html"); 
       
       
       s("contenido",$content);
       s("title", $title);
       
       s("metaKey",$metaKey ? $metaKey : "instituto, salute donna, ginecología, obstetricia, embarazo, odontología, endocrinología, clínica, cardiología, nutrición, psicología, urología, dermatología, cáncer, oncología, cirugía, estética, cirigía plástica, kinesiología, sil, leep, hpv, mama, mastitis, cáncer mama, cáncer ginecologíco, prostata, incontingencia urinaria, sobrepeso, stress, neurosis, lunares, depilación, protesis, parto, cesarea, colposcopia, criocirigía, lunar, piel, andrés ellena, rodolfo schiaffino, patricia monasterolo, pablo portillo, virginia morcillo, rodrigo acevedo, maria eugenia rodriguez cobos, gabriela díaz, maria virginia guardatti, beatriz solito, pamela puccio, maria florencia portillo, federico burone, salomé buniva, gisela cardo, gabriel lopez, luis ranieri, serrao enrique, paviolo daniela, emilio moreno");
       s("metaDesc",$metaDesc ? $metaDesc : "instituto, salute donna, ginecología, obstetricia, embarazo, odontología, endocrinología, clínica, cardiología, nutrición, psicología, urología, dermatología, cáncer, oncología, cirugía, estética, cirigía plástica, kinesiología, sil, leep, hpv, mama, mastitis, cáncer mama, cáncer ginecologíco, prostata, incontingencia urinaria, sobrepeso, stress, neurosis, lunares, depilación, protesis, parto, cesarea, colposcopia, criocirigía, lunar, piel, andrés ellena, rodolfo schiaffino, patricia monasterolo, pablo portillo, virginia morcillo, rodrigo acevedo, maria eugenia rodriguez cobos, gabriela díaz, maria virginia guardatti, beatriz solito, pamela puccio, maria florencia portillo, federico burone, salomé buniva, gisela cardo, gabriel lopez, luis ranieri, serrao enrique, paviolo daniela, emilio moreno");
       s("anio",date("Y"));
       s("especialidades",$especialidades);
       return parse("all");
    }
 
    function getEspecialidades($idCat){
        $rs = sql("select * from especialidades order by orden asc");
        $retorno = '<ul>';
        while ($fila = mfa($rs)){
            $nombre = cleanForShortURL($fila[nombre]);
            if ($idCat == $fila[id]){
                $seleccionado = ' seleccionado';
            }else{
                $seleccionado = '';
            }
            $retorno .= '<li><a href="'.$nombre.'_'.$fila[id].'.html" class="categoriaPrincipal'.$seleccionado.'" >'.$fila[nombre].'</a></li>';    
        }
        $retorno .= "</ul>";
        return $retorno;
    }
 
 
    function getField($tabla,$campo,$id_primaria){
        $fila = mysql_fetch_assoc(sql("select $campo FROM $tabla where id='$id_primaria'"));
        return $fila[$campo];
    }
     function getRow($tabla,$id_primaria){
        $fila = mysql_fetch_assoc(sql("select * FROM $tabla where id='$id_primaria'"));
        return $fila;
    }
    
    function enviarHTMLMail($from,$to,$titulo,$mensaje){
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'To: '.$to.'' . "\r\n";
        $headers .= 'From: '.$from.'' . "\r\n";
        mail($to, $titulo, $mensaje, $headers);
    }
  
    
    function divW($texto,$class){
        return '<div class="'.$class.'">'.$texto."</div>";
    }
    

    function fechaE($fecha,$e=1){
        $meses = array("Ene","Feb","Mar","Abr","May","Jun",
                       "Jul","Ago","Sep","Oct","Nov","Dic");
        $arr = explode("-",$fecha);
        return $arr[2] . ($e ? "<br>":" ").$meses[$arr[1]-1];
        
    }
    
    function mb_substrws($text, $length = 180) {
    if((mb_strlen($text) > $length)) {
        $whitespaceposition = mb_strpos($text, ' ', $length) - 1;
        if($whitespaceposition > 0) {
            $chars = count_chars(mb_substr($text, 0, ($whitespaceposition + 1)), 1);
            if ($chars[ord('<')] > $chars[ord('>')]) {
                $whitespaceposition = mb_strpos($text, ">", $whitespaceposition) - 1;
            }
            $text = mb_substr($text, 0, ($whitespaceposition + 1));
        }
        // close unclosed html tags
        if(preg_match_all("|(<([\w]+)[^>]*>)|", $text, $aBuffer)) {
            if(!empty($aBuffer[1])) {
                preg_match_all("|</([a-zA-Z]+)>|", $text, $aBuffer2);
                if(count($aBuffer[2]) != count($aBuffer2[1])) {
                    $closing_tags = array_diff($aBuffer[2], $aBuffer2[1]);
                    $closing_tags = array_reverse($closing_tags);
                    foreach($closing_tags as $tag) {
                            $text .= '</'.$tag.'>';
                    }
                }
            }
        }

    }
    return $text;
} 
  

}//end class
?>
