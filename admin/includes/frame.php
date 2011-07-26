<?php

function loge($txt){
    $f = fopen("log/logs.txt","a");
    fputs($f,$txt);
    fclose($f);
}

function sql($query){
    if ($rs = mysql_query($query)){
        return $rs;
    }else{
        loge("\n". $query ." ERROR :" . mysql_error());
    }
}

function mfa($rs){
    return mysql_fetch_assoc($rs);
}



if(defined("TEMPLATE")) return;
   
   define("TEMPLATE", 1);

   $classname = "Template";
   $root = "./templates/";
   $blocks = array();
   $vars = array();
   $unknowns = "keep";  // "remove" | "comment" | "keep"
   $halt_on_error = "yes";   // "yes" | "report" | "no"

   function set_file($name, $filename) {
             $classname = "Template";
             global $root;
             global $blocks;
             global $vars;
             global $unknowns;
             global $halt_on_error;
             $root = "./templates/";
             $blocks = 0;
             $blocks = array();
             $vars = 0;
             $vars = array();
             $unknowns = "keep";  // "remove" | "comment" | "keep"
             $halt_on_error = "report";   // "yes" | "report" | "no"
          extract_blocks($name, load_file($filename));
       }

    function set_var($var, $value) {
       global $vars;
         $vars["/\{$var}/"] = $value;
    }

    function s($var, $value) {
       global $vars;
         $vars["/\{$var}/"] = $value;
    }
   
    function parse($target, $block = "", $append = false) {
       global $blocks,$vars,$unknowns,$regs;
        if($block == "") {
            $block = $target;
        }
        if(isset($blocks["/\{$block}/"])) {
            if($append) {
                $vars["/\{$target}/"] .= @preg_replace(array_keys($vars), array_values($vars), $blocks["/\{$block}/"]);
            } else {
                $vars["/\{$target}/"] ="";
			    $vars["/\{$target}/"] = @preg_replace(array_keys($vars), array_values($vars), $blocks["/\{$block}/"]);
                 
			}
            
        } else {
            halt("parse: No existe ningun bloque llamado \"$block\"." . serialize($blocks));
        }
        
        return $vars["/\{$target}/"];
    }
    
    function pp($nombrebloque){
        return parse($nombrebloque,$nombrebloque,true);
        
    }

    function pparse($target,$archivo="", $block="", $append = false) {
           echo parse($target, $block, $append);
    }

    function p($block) {
       global $vars;
        return print($vars[$block]);
    }

    function get_vars() {
       global $vars;
        reset($vars);
        while(list($k,$v) = each($vars)) {
            preg_match('/^{(.+)}$/', $k, $regs);
            $vars[$regs[1]] = $v;
        }
        return $vars;
    }

    function get_var($varname) {
       global $vars;
            return $vars["/\{$varname}/"];
    }

    function get($varname) {
       global $vars;
        return $vars["/\{$varname}/"];
    }

    function load_file($filename) {
       global $root;
        if(($fh = fopen("$root/$filename", "r"))) {
            $file_content = fread($fh, filesize("$root/$filename"));
            fclose($fh);
        } else {
            halt("load_file: No se puede abrir $root/$filename.");
        }
        return $file_content;
    }

    function extract_blocks($name, $block) {
       global $blocks,$regs;
        $level = 0;
        $current_block = $name;
        $blocksa = explode("<!-- ", $block);
        if(list(, $block) = @each($blocksa)) {
            $blocks["/\{$current_block}/"] = $block;
            while(list(, $block) = @each($blocksa)) {
                preg_match('/^(BEGIN|END) (\w+) -->(.*)$/s', $block, $regs);
                switch($regs[1]) {
                    case "BEGIN":
                    $blocks["/\{$current_block}/"] .= substr( "\{$regs[2]}",1);
					$block_names[$level++] = $current_block;
                    $current_block = $regs[2];
                    $blocks["/\{$current_block}/"] = $regs[3];
                    break;
                    case "END":
                    $current_block = $block_names[--$level];
                    $blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    default:
                    $blocks["/\{$current_block}/"] .= "<!-- $block";
                    break;
                }
                unset($regs);
            }
        } else {
            $blocks["/\{$current_block}/"] .= $block;
        }
    }

    function halt($msg) {
      global $halt_on_error,$last_error;

        $last_error = $msg;
        if ($halt_on_error != "no")
            haltmsg($msg);
        if ($halt_on_error == "yes")
            die("<b>Halted.</b>\n");
        return false;
    }

    function haltmsg($msg) {
       print("<b>Template Error:</b> $msg<br>\n");
    }
?>