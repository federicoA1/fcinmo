<?php
/************************************************************************************
@author Federico Emiliani - Inetsolutions
*************************************************************************************/
include_once("classes/User.php");
class UltimateTable {

var $archivo_validacion = "includes/validacion.php";

var $tabla ="";
var $searchBy ="";
var $searchBlock="";

var $sqlcore = "";
var $titulo ="";

var $pk = "";
var $noshow="";

var $source = "";
var $order = "";
var $ad = "";
var $ad_ant ="";
var $page = "";
var $botones = "";

var $campos_simples="";
var $campos_especiales = array();

var $nombre_campos ="";
var $buscarpor ="";

var $filename = "";
var $errores ="";

var $showid ="";
var $afterInsert="";
var $espass="";
var $limit = 50;
var $truefalse = array();
var $parent =array();
var $masks = array();
var $sqlSearch = "";

function listar(){
   
	if (!$this->order) {
		$this->order = $this->pk;
	}
	if(!$this->ad){
		$this->ad ="ASC";
	}
    if ($_GET[ad]){
        $this->ad =$_GET[ad];
    }
    
	if (!$this->page) {
	    $this->page=1;
	}
	$empieza_en = $this->limit*($this->page-1);
	
	if ($this->sqlSearch)
		$temp_sql = $this->sqlcore . $this->sqlSearch;
	else{
		$temp_sql =$this->sqlcore;
	}
	$SQL= $temp_sql ." ORDER by $this->tabla.$this->order $this->ad LIMIT $empieza_en, $this->limit";
	
   
    set_file("listar","scriba/common_listado.html");
	set_var("title",$this->tituloL);
    
	$rsaux = sql($temp_sql);

	if(mysql_num_rows($rsaux)){

	$rs = mysql_query($SQL);

	$cant_registros = mysql_num_rows($rsaux);
	$cant_paginas = ($cant_registros / $this->limit)+1;

	if ($cant_paginas > 2) {


		if ($this->page != 1) {
		      set_var('seccion',$seccion);
		      set_var('order',$this->order);
			  set_var('pagina',($this->page-1));
			  set_var('Nro_pagina','<<');
		  	  set_var('tipo',$this->ad);
			  set_var("source",$this->source);
			  parse("paginas","paginas",true);
		}

		$j=1;
		while($j<$cant_paginas){
		  	  set_var('seccion',$seccion);
		      set_var('order',$this->order);
			  set_var('pagina',$j);
			  if ($j == $this->page) {
			      set_var('Nro_pagina',"<strong>[$j]</strong>");
			  }
			  else
			  {
			  	set_var('Nro_pagina',"$j");
		  	  }
			  set_var('tipo',$this->ad);
			  set_var("source",$this->source);
			  $j++;
			  parse("paginas","paginas",true);
		} // while

		if ($this->page <= $cant_paginas-1) {
		      set_var('seccion',$seccion);
		      set_var('order',$this->order);
			  set_var('pagina',($this->page+1));
			  set_var('Nro_pagina','>>');
		  	  set_var('tipo',$this->ad);
			  set_var("source",$this->source);
			  parse("paginas","paginas",true);
		}

	}else{
		set_var("paginas","");
		}
	//pparse('listar');
    
    
    $buscadorHTML = '';
    if ($this->buscador['campos']){
        $selectHTML ="<div class='buscador'>
        <form action='?act=doBuscar' method='post'>
        Buscar por: <select name='buscador' id='buscador'> ";
        foreach($this->buscador['campos'] as $elemento){
            $elementoTexto = $this->nombre_campos[$elemento] ? $this->nombre_campos[$elemento] : $elemento;
            $seleccionado = $this->buscador['seleccionado'] == $elemento ? ' selected="selected" ': '';
            $selectHTML .= "<option value='$elemento' $seleccionado>$elementoTexto</option>";
        }
        $selectHTML .= "</select> ";
        $selectHTML .= " <input type='text' id='texto' name='texto' value='{$this->buscador['seleccionadoTXT']}' size='40'/>";
        $selectHTML .= " <input type='submit' value='Buscar'/> <a href=\"?act=I\">Quitar filtros</a></form></div>";
        $buscadorHTML = $selectHTML;
    }
    s("search_block", $buscadorHTML);
    


	if (!$rs) {
		return false;
	}
	$typearr = array();
	//$ncols = $rs->FieldCount() ;

	$ncols = mysql_num_fields($rs);
	$hdr = '<TABLE class="uabm_table" cellspacing="0" cellpadding="1" >  <tr class="uabm_table_head">';
	for ($i=0; $i < $ncols; $i++) {
	 		$field = mysql_fetch_field($rs,$i);
			if (($field->name != $this->pk) or ($this->showid ==1) )  {
				if ($this->noshow[$field->name] != 1) {
					if ($this->campos_especiales[$field->name][1] != 3) {
						if ($this->nombre_campos[$field->name]){
					  	  $fname = $this->nombre_campos[$field->name];
						  }
						else
							{
							$fname = htmlspecialchars($field->name);
							}
                        if ($field->name == $this->order){
                            $linkClass = " ordered";
                        }else{
                           $linkClass =""; 
                        }
						$hdr .= '<td >'.'<a href="'.$this->source.'?seccion='. "$seccion&order=$field->name&page=$this->pagina&ad=$this->ad\" class=\"link_titulo $linkClass\">".    $fname . '</a> </td>';
					}
				}
			}
		}
	for ($j=0; $j < count($this->botones);$j++){
			$hdr .= "<td></td>";
	}

	$listado_completo = $hdr.'</tr>';
	$i2 = 0;


    $cant_filas=mysql_num_rows($rs);
 	while($i2<$cant_filas){
		//cambio de colores entre filas
 		$id = mysql_result($rs,$i2,$this->pk);
			if ($i2 % 2 == 0)
			    $table .= '<tr class="par" recordID="'.$id.'">';
			else
				$table .= '<tr class="impar" recordID="'.$id.'">';

			$i2++;

			if ($this->showid ==1)
			    $inicio = 0;
			else
				$inicio = 1;

			for ($i=$inicio; $i < $ncols; $i++) {
				/*seteo el valor de id que luego uso para setear los botones*/
 				$campo = mysql_fetch_field($rs,$i);


			if ($this->noshow[$campo->name] != 1)
					if ($this->campos_especiales[$campo->name][1] != 3) {
						$mostrame = mysql_result($rs,$i2-1,$campo->name);
						/*claves foraneas*/
						if ($this->fk[$campo->name]) {
						  $lala = "select ".$this->fk[$campo->name][1]." from " . $this->fk[$campo->name][0]." " . $this->fk[$campo->name][3]." where ".$this->fk[$campo->name][2]. "=" .$mostrame ;
						  
                          $rs2 = sql($lala);
                          echo mysql_error();
						  $campos = mysql_fetch_array($rs2);
						  $mostrame = $campos[0];
						}
						/*campos especiales*/
						if ($this->campos_especiales[$campo->name]) {
						  	if ($this->campos_especiales[$campo->name][1]==1) {

								include_once("campos_especiales/$this->tabla". ".php");
								$funcion = $this->tabla."_".$campo->name."_l";
								$mostrame = $funcion();
							}
						}
						//muestra la fecha correctamente
						if ($campo->type == "date"){
								$fecha_array = explode("-",$mostrame);
					   		    $fecha_ordenada = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];
								$mostrame =$fecha_ordenada;
						}
                        
                        $td = $this->getTDfor($campo->name,$mostrame);
                        
                        //cambio de los valores para mostrar la imagen en el caso que sea TRUEFALSE
                        if ($this->truefalse[$campo->name] == 1){  ;
                            if ($mostrame)
                                $mostrame = "<img src='images/enable.png'/>";
                            else
                                $mostrame = "<img src='images/disable.png'/>";
                        } 
						$table .= $td . $mostrame."</td>";
					}
				}
			//imprime los botones
			for ($j=0; $j < count($this->botones);$j++){
				$botonstr = str_replace("{id}",$id ,$this->botones[$j]);
				$table .= "<td>" . $botonstr ."</td>";
			}
			$table .= "</tr>";
		} // while

	}else{
		set_var("paginas",$this->noHayRegistros);
		set_var("search_block","");
	}

	$listado_completo .= $table."</TABLE>\n\n";
    
    if ($this->linksT) {
        while (list($key, $value) = each($this->linksT)){
            $links .= "<a href='$value' class='link_box'>$key</a>";
        }
        set_var("linksT",$links);
    }else{
        set_var("linksT","");
    }
    
	set_var("listado_completo",$listado_completo);
	return parse('listar');
 }



/*IN PLACE EDITOR*/
function getTDfor($fieldName,$valor){
	if ($this->inplace[$fieldName] == "simple_text"){
			return '<td class="simple_text" fieldName="'.$fieldName.'">';
	}
	if ($this->inplace[$fieldName] == "long_text"){
			return '<td class="long_text" fieldName="'.$fieldName.'">';
	}
    if ($this->inplace[$fieldName] == "limit_text"){
            return '<td class="limit_text" fieldName="'.$fieldName.'">';
    }
    
	if ($this->selects[$fieldName]){
			return '<td class="'.$fieldName.'" fieldName="'.$fieldName.'">';
	}
    if ($this->truefalse[$fieldName]){
        return  '<td class="truefalse" fieldName="'.$fieldName.'" align="center"  valorAnterior="'.$valor.'">';
    }
	return "<td>";
}

function partialSave($fieldName,$recordId,$value){
	//echo "update $this->tabla SET $fieldName ='$value' where $this->pk = $recordId";
	
   //$value = str_replace(array("á","é","í","ó","ú","ñ"),array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;"),$value);
    if ($this->inplace[$fieldName] =="long_text" or $this->inplace[$fieldName] =="limit_text")
		$value = nl2br($value);
	mysql_query("update $this->tabla SET `$fieldName` ='$value' where $this->pk = $recordId");
	return $value;
}

function getSearch(){
	set_file('listado','common_search.html' );
	while (list($key, $value) = each ($this->searchBy))
			{
	  		set_var('nombre_campo',$key);
			if ($value ="simple_text"){
				set_var('tipo_campo','<input type="text" name="'. $key .'" value=""></input>');
			}
			parse("campo","campo",true);
			}
	set_var("texto_boton","Search");
	return parse("bloque","bloque",true);
}

function doSearch(){

		$conector = "AND";
	 	$linea = "";
		while (list($key, $value) = each ($this->searchBy))
			if ($_REQUEST[$key]) {
					$linea .= " ".$conector ." ". $key . " LIKE '%" . $_REQUEST[$key]. "%'" ;
			}
		$linea = substr($linea,4);
	if ($linea){
		$linea = " WHERE $linea";
	}

	$this->sqlSearch = $linea;

	return $this->listar();


}
	/******************************************************************************
	* FUNCION ABM
	* esta funcion se encarga de la pantalla principal del ABM el cual luego llama a los
	*  modulos correspondientes
	* ******************************************************************************/
function ABM() {
	set_file('listado','scriba/common_insertar.html' );
	$head= '';
	if ($this->mensajeABM){
        set_var("mensaje","<div class='mensaje'>".$this->mensajeABM."</div>");
    }else{
        set_var("mensaje","");
    }

    set_var('filename',$this->filename);

	if ($_REQUEST[$this->pk]== "") {
	    $modoins = "INSERTAR";
		set_var('modo','a');
		set_var("titulo",$this->tituloA);
	}else{
		 $modoins = "MODIFICAR";
		 set_var('modo','m');
		 set_var("titulo",$this->tituloM);
		 if (!$_POST) {
         	$res = mysql_query("SELECT * FROM $this->tabla WHERE $this->pk =". $_REQUEST[$this->pk] )	;
			$lala =mysql_fetch_assoc($res);
		    while (list($key, $value) = each ($lala)){
	  			$_REQUEST[$key] = $value;
			}
		  }//if
	}//else
	$func_counter = 0;
	$after_form = "";

	set_var ("head",$head);
	$rs =sql("SHOW COLUMNS FROM $this->tabla");

	while ($column = mysql_fetch_assoc($rs)){
	  //veo si es una clave primaria
	 if (!(($this->campos_especiales[$column[Field]])and(!$this->campos_especiales[$column[Field]][0])))
	   if (!($column[Field] == $this->pk)) {
           if ($this->parent && $column[Field] == $this->parent[0] ){
           
               $linea .= '<input type="hidden" name="'. $column[Field] ."\" value = \"" . $this->parent[1]  ."\" ></input>"; 
           }
           else{
		            //verifico si la columna en cuestion es un campo especial de clave foranea
		             if ($this->campos_especiales[$column[Field]][1]) {
		                  include_once("campos_especiales/$this->tabla". ".php");
			              $funcion = $this->tabla."_".$column[Field]."_as";
			              $linea = $funcion();
			             }
		             else    //cuidado las {} no son necesaria ya que la linea que me interesa es solo 1
		              if ($this->fk[$column[Field]]) {
		                 $tablafk = $this->fk[$column[Field]][0];
			             $campoS =  $this->fk[$column[Field]][1];
			             $campoI =  $this->fk[$column[Field]][2];

			            //agregado 28-9-05
			            if ($this->fk[$column[Field]][3])
			                $agregado = $this->fk[$column[Field]][3];
			            else
				            $agregado = "";
                         
			             $res3 = sql("SELECT $campoI,$campoS FROM $tablafk $agregado");
                         echo mysql_error();
			             $linea = "";
			             $linea = '<select name="'.$column[Field].'">';
	   		             $i = 0;
			             while ($fila = mysql_fetch_array($res3)) {
				                if ($_REQUEST[$column[Field]]==$fila[0]) {
						            $sel = "selected";
				                }else{
						            $sel = "";
						            }
					            $linea .=  '<option value="'.$fila[0].'" '. $sel. '>'.$fila[1].'</option>';
			 		            $i++;
				            }
			             $linea .=	"</select>";
		             }
		             else{
		                switch($column[Type]){
				            case 'varchar':
				 	            if ($column->max_length > 50) {
				 	             $linea .=  '<input type="text" name="'. $column[Field] ."\" value = \"" . $_REQUEST[$column[Field]] ."\" " . "size=\"100\" maxlength=\"$column->max_length\" ></input>";
				 		            }else{
					            $linea .= '<input type="text" name="'. $column[Field] ."\" value = \"" . $_REQUEST[$column[Field]] ."\" " . "size=\"$column->max_length\" maxlength=\"$column->max_length\" ></input>";
				 	            }
					            break;
				            case 'blob':
					            $linea .='<textarea name="'. $column[Field] . '" cols="30" rows="10" id="'. $column[Field] . '">' . $_REQUEST[$column[Field]] .'</textarea>';
					            break;
				            case 'int':
				                $linea .=  '<input type="text" name="'. $column[Field] ."\" value = \"" . $_REQUEST[$column[Field]] ."\" ></input>";
				 	            break;
				            case 'longtext':
				            case 'text':
				             $linea .='<textarea name="'. $column[Field] . '" cols="90" rows="10" id="'. $column[Field] . '" style="height=500px;">' . $_REQUEST[$column[Field]] .'</textarea>';
                             if (!$this->noHTML[$column[Field]])
                             $linea .= '<script type="text/javascript">
                                             $(document).ready(function() {
                                                var oFCKeditor = new FCKeditor( "'. $column[Field].'" ) ;
                                                oFCKeditor.ToolbarSet = "Basic" ;
                                                oFCKeditor.BasePath = "fckeditor/" ;
                                                oFCKeditor.Height = 230;   
                                                oFCKeditor.ReplaceTextarea() ;
                                                });</script>';
					            break;
				            case 'date':
                            $a= explode("-",$_REQUEST[$column[Field]]);
                            $_REQUEST[$column[Field]] = $a[2]."-". $a[1]."-". $a[0] ;
                            
                            
				            case 'datetime':
					          $linea .= '<input type="text" name="'. $column[Field] ."\" id='". $column[Field]."'  value = \"" . $_REQUEST[$column[Field]] ."\" ></input>";                                       
                              if ($this->masks[$column[Field]]){
                                   $mask_line .= '$("#'. $column[Field] .  '").mask("'.$this->masks[$column[Field]].'");';
                              }
				            break;
				            default:
				 	            $linea .= '<input type="text" name="'. $column[Field] ."\" value = \"" . $_REQUEST[$column[Field]] ."\" ></input>";
				 	            break;

			                } // switch
                        
                            if ($this->truefalse[$column[Field]]){
                                $checkedd = $_REQUEST[$column[Field]] ? ' checked="checked" ' : "";
                                $linea = '<input type="checkbox" name="'. $column[Field] .'" id="'. $column[Field] .'" value="1" '.$checkedd.' >';
                            }
                            
                            //overwrite simple rule in case its a file
                            if($this->isfile[$column[Field]]){
                                 $linea = '<input type="file" accept="image/jpeg" name="'. $column[Field] .'" value = "' . $_REQUEST[$column[Field]] .'" ></input>';
                            }
			            }
			            if (!$this->nombre_campos[$column[Field]]) {
			                set_var("nombre_campo",$column[Field]);
			            }else{
				             set_var("nombre_campo",$this->nombre_campos[$column[Field]]);
			            }
			            //HUBO error???
			            if ($this->errores[$column[Field]]) {
			                set_var("error",$this->validar[$column[Field]][3]);
			            }
			            else{
			                set_var("error","");
			            }
			            set_var("tipo_campo",$linea);
			            parse("campo","campo",true);
			            $linea="";
            }
			}//fin del if (es pk?) si lo es tiene que agregarlo en un campo hidden ;)
			else{
				set_var ("hidden",'<input type="hidden" name="'. $column[Field]
				."\" value = \"" . $_REQUEST[$column[Field]] ."\" ></input>");
				}
		}

		set_var ("after_form",$after_form);
		set_var ("texto_boton",$modoins);
		set_var ("modoins",$modoins);
		set_var ("tabla",$tabla);
		set_var ("pk",$pk);
        set_var("script", '
            $(document).ready(function() {
                      '.$mask_line.'
           });');
		parse("bloque","bloque",true);
		return parse('listado',"listado",true);
	}


/***********************************************************
* FUNCION DE ALTA
* Hace el alta de los parametros ingresados
* **********************************************************/
function alta($bloque){

	include_once($this->archivo_validacion);

	$rs =mysql_query("SHOW COLUMNS FROM $this->tabla");

		$this->errores = "";
		while ($column = mysql_fetch_assoc($rs)){
			if ($this->validar[$column[Field]]) {

			    if ( (!$this->validar[$column[Field]][0]($bloque[$column[Field]],$this->validar[$column[Field]][1],$this->validar[$column[Field]][2])) ) {
			        //or (!is_clean_text($bloque[$column->name]))
					//marca las columnas con errores (luego se chequea el valor de dicho vector y se
					//muestra el mensaje correspondiente
					$this->errores[$column[Field]] = 1;
		   		}
			}
		}//end foreach

		//Una vez que se que erroes ocurrieron tengo que llamar a la funcion de ABM con un vector nuevo que contiene
		//los valores viejos y los mensajes de error correspondientes
		if ($this->errores) {
		    $this->ABM($bloque);

		}else{

		//	$tabla_id = 'sequencia_tabla_' . $this->tabla;
        
		$rs =mysql_query("SHOW COLUMNS FROM $this->tabla");
		while ($column = mysql_fetch_assoc($rs)){
				//si es date lo tiene que reordenar
				//echo $column[Type];
                if (strpos($column[Type],"varchar") === 0)
                   if (!$this->isfile[$column[Field]])
                        $bloque[$column[Field]] = $bloque[$column[Field]];
               
                $campos .= "`$column[Field]`,";

				if (($column[Type] == "date") or ($column[type] == "datetime")) {
					$fecha_array = explode("-",$bloque[$column[Field]]);
	 				$bloque[$column[Field]] = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];
				}
				//se fija si no es la clave primaria


				if ($column[Field] == $this->pk) {
				    $id_del_registro = "";
					$bloque[$column[Field]] ="NULL"; //para un Mysql Pedorro
					//$bloque[$column[Field]] ="''";
				}

				if (($this->campos_especiales[$column[Field]]) and ($this->campos_especiales[$column[Field]][0] == 0))
				  {
				    /*include_once("campos_especiales/".$this->tabla. ".php");
					$func= $this->tabla . "_". $column[Field] ."_a" ;
					$bloque[$column[Field]] = $func();*/
				}

				if ($this->espass[$column[Field]])
					$valores .= "MD5('". $bloque[$column[Field]]."'),";

				if ($this->inplace[$column[Field]] =="long_text")
                   $bloque[$column[Field]] = nl2br($bloque[$column[Field]]);
                
                if ($this->isfile[$column[Field]]){
                    $numero = rand(100,5000);
                   // echo $bloque[$column[Field]]['tmp_name']; 
                    if ($bloque[$column[Field]]['name']){
                        copy($bloque[$column[Field]]['tmp_name'],"../userfiles/image/".$numero.$bloque[$column[Field]]['name']);
                        $bloque[$column[Field]] =  $numero.$bloque[$column[Field]]['name'];
                    }
                }
                
                
                if ($column[Field] != $this->pk)
					$valores .= "'". $bloque[$column[Field]]."',";
				else
					$valores .=  $bloque[$column[Field]]." , ";

			}
			$campos[strlen($campos)-1] = ' ';
			$valores[strlen($valores)-1] = ' ';
			$SQL = "INSERT INTO `$this->tabla` ($campos) VALUES ($valores);"	;

			//echo $SQL . "<br>";
	        if (mysql_query($SQL) == false ) {
	                print 'no se pudo insertar: ' .mysql_error() . '<br>';
	        }else{
                if ($this->afterInsert) {
                   //include_once("abml/after_insert_".$this->tabla.".php");
                   }
                
                return mysql_insert_id();
            }
			
		}

	}

/***********************************************************
* FUNCION DE MODIFICACION
* Modifica los parametros en la base de datos
* **********************************************************/
function modi($datos){

	include_once($this->archivo_validacion);

	$rs =mysql_query("SHOW COLUMNS FROM $this->tabla");
	$this->errores = "";

	while ($column = mysql_fetch_assoc($rs)){
		if ($this->validar[$column[Field]]) {
		    if ( (!$this->validar[$column[Field]][0]($datos[$column[Field]],$this->validar[$column[Field]][1],$this->validar[$column[Field]][2])) ) {
		        //marca las columnas con errores (luego se chequea el valor de dicho vector y se
				//muestra el mensaje correspondiente
				$this->errores[$column[Field]] = 1;
		   		}
		}
	}//end foreach


	if ($this->errores) {
	    $this->ABM($datos);
	}else{
		$rs =mysql_query("SHOW COLUMNS FROM $this->tabla");


		while ($column = mysql_fetch_assoc($rs)){

			if ($column[Field]!= $this->pk) {
			    if (!(($this->campos_especiales[$column[Field]]) and ($this->campos_especiales[$column[Field]][0] == 0)))

                    if (strpos($column[Type],"varchar") === 0)
                       if (!$this->isfile[$column[Field]])
                            $datos[$column[Field]] = $datos[$column[Field]];
                
                 
                
					 if ($column[Type] == "date") {
							$fecha_array = explode("-",$datos[$column[Field]]);
 							$datos[$column[Field]] = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];
							$campos .= "`$column[Field]` = '". $datos[$column[Field]]. "' ,";
						}else{
						      if ($this->isfile[$column[Field]]  ){
                                    $numero = rand(100,5000);
                                    if ($datos[$column[Field]]['name']){
                                        copy($datos[$column[Field]]['tmp_name'],"../userfiles/image/".$numero.$datos[$column[Field]]['name']);
                                        $datos[$column[Field]] =  $numero.$datos[$column[Field]]['name'];
                                        $campos .= "`$column[Field]` = '". $datos[$column[Field]]. "' ,";
                                    }
                              }else{
                                   $campos .= "`$column[Field]` = '". $datos[$column[Field]]. "' ,";
                             }//else if file
					}//else date  
                    
                    
                    
				}//if
			}//while

		$campos[strlen($campos)-1] = ' ';
		$SQL ="UPDATE $this->tabla set $campos WHERE $this->pk=".$_REQUEST[$this->pk];
		if (mysql_query($SQL) == false ) {
                print 'no se pudo modificar: ' .mysql_error() . '<br>';
        }
		//$this->listar();
	}//else

}
/***********************************************************
* FUNCION DE ELIMINACION
* Modifica los parametros en la base de datos
* **********************************************************/

function del(){
	global $db;
	//	mysql_select_db($this->dbname,$db);
	$SQL ="DELETE  from $this->tabla WHERE ".$this->pk."=".$_REQUEST[$this->pk];
	if (mysql_query($SQL) == false ) {
        print 'no se pudo eliminar: ' .mysql_error() . '<br>';
    }
}

/***********************************************************
* FUNCION DE Muestra
* Modifica los parametros en la base de datos
* **********************************************************/


function mostrar(){
	set_file('listado','common_mostrar.html' );

	//titulo de la seccion
	set_var("titulo",$this->tituloS);

	global $db;
	mysql_select_db($this->dbname,$db);
	$SQL ="SELECT * from $this->tabla WHERE id=".$_REQUEST[$this->pk];
	$rs = &$db->execute($SQL);
	$ncols = $rs->FieldCount() ;
	$columns = $db->MetaColumns($this->tabla);
	//for empieza de 1 no de 0 x el id
	$i = 0;
	foreach ($columns as $column){
		if (!($column->name == $this->pk))
		  if(!$this->fk[$column->name]){
			  if (!($this->campos_especiales[$column->name][1])) {
				if ($i2 % 2 == 0) {
	 			    set_var("color","#CED7DE");
	 			}
	 			else
	 			{
	 				set_var("color","#CCCCCC");
	 			}
	 			$i2++;


				switch($column->type){
				  	case "text":
				  		$linea= '<table width = "400">
								<tr>
								<td>'.$rs->fields[$column->name].'

								</td>
								</tr>
								</table>';
				  		break;
				  	case "date":

	 					$fecha_array = explode("-",$rs->fields[$column->name]);
			   		    $fecha_ordenada = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];
						$linea =$fecha_ordenada;
				  		break;
				  	default:
						$linea = $rs->fields[$column->name];
						if ($this->fk[$column->name]) {
						  $lala = "select * from " . $this->fk[$column->name][0]." where ".$this->fk[$column->name][2]. "=" .$rs->fields[$column->name];;
						 // echo $lala;
						  $rs2 = &$db->Execute($lala);
						  $campos = $rs2->fetchrow();
						  $linea = $campos[$this->fk[$column->name][1]];
				  		}

					}
					if (!$this->nombre_campos[$column->name]) {
					    set_var("nombre_campo",$column->name);
					}else{
						 set_var("nombre_campo",$this->nombre_campos[$column->name]);
					}

					set_var("tipo_campo",$linea);
					parse("campo");
			}//if noshow
		}//no fk
		else{
			if ($i2 % 2 == 0) {
		 	    set_var("color","#CED7DE");
		 	}
		 	else
		 	{
		 	set_var("color","#CCCCCC");
		 	}
		 	$i2++;

			///si el campo en cuestión es una clave foranea entonces.....
			$SQLFK = "select * from " . $this->fk[$column->name][0]." where ".$this->fk[$column->name][2]. "=" .$rs->fields[$column->name];;
			$resultset = &$db->Execute($SQLFK);
			$campos = $resultset->fetchrow();

			if (!$this->nombre_campos[$column->name]) {
			    set_var("nombre_campo",$column->name);
			}else{
				 set_var("nombre_campo",$this->nombre_campos[$column->name]);
			}
			set_var("tipo_campo", $campos[$this->fk[$column->name][1]]);
			parse("campo");
		}
	}
	set_var('filename',$this->filename);
	set_var('seccion', $_SESSION[seccion]);
	parse("bloque");
	pparse("listado");
}


function buscar(){

	if (!$this->buscarpor) {
	    die("<font color=\"#FF0000\"> <b> no se eligió ningun campo por el cual se desea buscar</b></font>");
	}

	global $db;
		mysql_select_db($this->dbname,$db);
	set_file('listado','common_insertar.html' );
	set_var("texto_boton", "Buscar");

	$columns = $db->MetaColumns($this->tabla);

	foreach($columns as $column) {
		if ($this->buscarpor[$column->name]) {


		if ($this->fk[$column->name]) {
	     $tablafk = $this->fk[$column->name][0];
		 $campoS =  $this->fk[$column->name][1];
		 $campoI =  $this->fk[$column->name][2];

		 $res3 = $db->Execute("SELECT * FROM $tablafk");
		 $linea = "";
		 $linea = '<select name="'.$column->name.'">';
   		 $i = 0;
		 while ($i<$res3->RecordCount()) {
			    if ($_REQUEST[$column->name]==$res3->fields[$campoI]) {
					$sel = "selected";
			    }else{
					$sel = "";
					}
				$linea .=  '<option value="'.$res3->fields[$campoI].'" '. $sel. '>'.$res3->fields[$campoS].'</option>';
		 		$i++;
				$res3->MoveNext();
		 }
		 $linea .=	"</select>";
	 }
	 else{   switch($column->type){
			case 'varchar':
			 	if ($column->max_length > 80) {
			 	 $linea .=  ' <textarea name="'.
				 			$column->name .'"rows="2" cols="'. ($column->max_length / 2). '">'. $_REQUEST[$column->name].'</textarea>';
			 	}else{
				$linea .= '<input type="text" name="'. $column->name ."\" value = \"" . $_REQUEST[$column->name] ."\" " . "size=\"$column->max_length\" maxlength=\"$column->max_length\" ></input>";
			 	}
				break;
			case 'int':
			    $linea .=  '<input type="text" name="'. $column->name ."\" value = \"" . $_REQUEST[$column->name] ."\" ></input>";
			 	break;
			case 'text':
			 $linea .= '<textarea name="'. $column->name . '" cols="30" rows="10">' . $_REQUEST[$column->name] .'</textarea>';

			 //le agrego unas linas por el editor WYSIWYG HTMLAREA, el cual esta genial!!!
			 $linea .='<script language="JavaScript1.2" defer>
						editor_generate(\'' . $column->name. '\');
						</script>';

				break;
			case 'date':
				if ($_REQUEST[$column->name] != "") {
				    $fecha_array = explode("-",$_REQUEST[$column->name]);
 					$_REQUEST[$column->name] = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];
				}

				$linea .= '<input name="' .$column->name. '" type="text" id="fecha_dia" value="' . $_REQUEST[$column->name] .'" size="15">
       					<a href="javascript:cal'. $func_counter. '.popup();"><img src="img/cal.gif" width="16" height="16"
						border="0" alt="precione aqui para seleccionar la fecha"></a> ';
				$after_form .= '<script language="JavaScript">
							<!--
							var cal'. $func_counter. ' = new calendar1(document.forms[\'formu\'].elements[\'' .$column->name. '\']);
							cal'. $func_counter. '.year_scroll = true;
							cal'. $func_counter. '.time_comp = false;
								-->
							</script>';
			$func_counter++;
			break;
			default:
			 	$linea .= '<input type="text" name="'. $column->name ."\" value = \"" . $_REQUEST[$column->name] ."\" ></input>";
			 	break;

		} // switch


		}
		if (!$this->nombre_campos[$column->name]) {
		    set_var("nombre_campo",$column->name);
		}else{
			 set_var("nombre_campo",$this->nombre_campos[$column->name]);
		} 

		set_var("tipo_campo",$linea);
		parse("campo","campo",true);
		$linea="";

	}
}

	$head= '<script language="JavaScript" src="calendar1.js"></script>';
	set_var("filename",$this->filename);
	set_var("modo","hacer_busqueda");
	set_var("hidden","");
	set_var("head","");
	set_var("titulo","Buscar");
	set_var("after_form",$after_form);
	set_var("modoins",$modoins);
	set_var("tabla",$tabla);
	set_var("pk",$pk);

	parse("bloque","bloque",true);
	pparse('listado');

}//FIN BUSCAR

function hacer_busqueda(){
  global $db;
  //primero tengo que armar la cadena WHERE
    $linea = "";
	$conector = "AND";
 	$columns = $db->MetaColumns($this->tabla);
	$linea = "";
	foreach($columns as $column) {
		if ($this->buscarpor[$column->name] and $_REQUEST[$column->name]) {
				$linea .= " ".$conector ." ". $column->name . " LIKE '%" . $_REQUEST[$column->name]. "%'" ;}
	}
	$linea = substr($linea,4);
	$linea = " WHERE $linea";
	$this->sqlcore = $this->sqlcore . $linea;
	$this->listar();
}//FIN HACER BUSQUEDA


    function activate($arr){
        if ($arr['valorAnterior']) {
            $change = '0';
            $mostrame = "<img src='images/disable.png'/>";
            }
        else {
            $change = '1';
            $mostrame = "<img src='images/enable.png'/>";
        }
        $sql = "UPDATE $this->tabla SET $arr[fieldName] = $change WHERE $this->pk = $arr[recordId]";
        mysql_query($sql);
        return $mostrame;
    }

}//FIN CLASE
?>