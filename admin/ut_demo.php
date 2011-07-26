<?php
include_once("includes/connect.php");
include_once("cl_ultimate_table.php");
session_start();  
$filename =  basename($PHP_SELF);

switch($_REQUEST[act]){
    case "doBuscar" :
        $_SESSION[listado]->buscador['seleccionadoTXT'] = $_REQUEST[texto];
        $_SESSION[listado]->sqlSearch = " where  $_REQUEST[buscador] like '%$_REQUEST[texto]%' ";
        $_SESSION[listado]->noHayRegistros = "Sorry no results for <b>$_REQUEST[texto]</b> <a href='$filename?act=I'>Return</a>";
        echo User::prepareEmptyIndex($_SESSION[listado]->listar(),"Admin Links") ; 
        break;
    case "I":
      

        $listado = new UltimateTable();
        $sujeto = "Email";
        $listado->verbo = $sujeto;
        $listado->tituloA   = "Add $sujeto";
        $listado->tituloM   = "$sujeto";
        $listado->tituloS   = "$sujeto";
        $listado->tituloL   = "$sujeto";
        $listado->noHayRegistros = "No $sujeto";

        $listado->dbname    = $dbname;
        $listado->dblink    = $dblink;
        $listado->tabla     = "emails";
        $listado->linksT['BACK']   = "index.php"; 
        $listado->linksT['INSERT'] = "$filename?act=am";

        $listado->pk = "id";
        $listado->sqlcore = "SELECT * from $listado->tabla  ";
        $listado->filename = $filename;
     
        
        $listado->inplace = array(  "header"=>"simple_text",
                                    "col1"=>"simple_text",
                                    "col2"=>"simple_text",
                                    "col3"=>"simple_text");
        $listado->noshow = array("group"=>1,"registered"=>1,"name"=>1);
        

        $listado->botones[0] ='<a href="'.$filename.'?tabla=' . $listado->tabla . '&'.$listado->pk.'={id}&pk=' . $listado->pk . '&act=d"><img src="images/btn_delete.png" alt="Delete"  border="0" ></a>
          &nbsp;&nbsp;';
        $listado->buscador['campos'] =array("email");
         

        $listado->ad =$_REQUEST[ad];
        $listado->order =$_REQUEST[order];
        $listado->page = $_REQUEST[page];
        $_SESSION[listado]=$listado;
        
        
         echo User::prepareEmptyIndex($listado->listar(),"Admin Links") ; 
        
        
        break;


    case "am":
            echo User::prepareEmptyIndex($_SESSION[listado]->ABM(),"");
            break;
    case "a":   
                $_SESSION[listado]->alta(array_merge($_POST,$_FILES));
                echo User::prepareEmptyIndex($_SESSION[listado]->listar(),"Admin Links") ; 
             break;
    case "m":   
               $_SESSION[listado]->modi(array_merge($_POST,$_FILES));
               echo User::prepareEmptyIndex( $_SESSION[listado]->listar(),"Admin Links") ;       
             break;                      
    
    case "d": $_SESSION[listado]->del();
                 echo User::prepareEmptyIndex($_SESSION[listado]->listar(),"Admin Links") ; 
             break;         
    case "partialsave":
            echo $_SESSION[listado]->partialSave($_POST[fieldName],$_POST[recordId],utf8_decode($_POST[common_input]));
            break;
    case "doSearch":
            echo $_SESSION[listado]->doSearch();
            break;
    case "active": echo $_SESSION[listado]->activate($_REQUEST); 
                    break;        
     default:
                $_SESSION[listado]->ad =$_REQUEST[ad];
                $_SESSION[listado]->order =$_REQUEST[order];
                $_SESSION[listado]->page = $_REQUEST[page];
                  echo User::prepareEmptyIndex($_SESSION[listado]->listar(),"Admin Links") ; 

}//switch





?>
