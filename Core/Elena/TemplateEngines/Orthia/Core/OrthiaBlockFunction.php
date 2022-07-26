<?php

namespace Clsk\Elena\TemplateEngines\Orthia\Core;

use Clsk\Elena\TemplateEngines\Orthia\Core\ClearSkyOrthiaException;


class OrthiaBlockFunction
{
    public $terms = "";
    public $parsemode = "phper";
    public $if_pathed = True;
    public $elif_pathed = True;
    public $copy_parts = array();
    public $block_name = "";
    public $params;

    public function __construct($params, $parsemode, $isHTMLSC = True)
    {
        $this->params = $params;
        $this->parsemode = $parsemode;
        $this->isHTMLSC = $isHTMLSC;
    }

    public function if($terms)
    {
        extract($this->params);

        if(eval("return ".$terms.";")){
            return [True, "if"];
        }else{
            $this->if_pathed = True;
            return [False, "if"];
        }
    }

    public function elif($terms)
    {
        extract($this->params);

        if(eval("return ".$terms.";") && $this->if_pathed){
            return [True, "if"];
        }else{
            if($this->if_pathed){
                return [False, "if"];
            }else {
                $this->elif_pathed = True;
                return [False, "if"];
            }
        }
    }

    public function elseif($terms)
    {
        return $this->elif($terms);
    }

    public function else()
    {
        extract($this->params);

        if($this->if_pathed || $this->elif_pathed){
            return [True, "if"];
        }else{
            return [False, "if"];
        }
    }

    public function for(String $terms)
    {
        $this->terms = $terms;
        return True;
    }

    public function comment()
    {
        return True;
    }

    public function foreach(String $terms)
    {
        extract($this->params);
        $this->terms = $terms;
        return True;
    }

    public function parts_block(String $block_name)
    {
        $this->block_name = $block_name;
        return True;
    }

    public function OrthiaRewriteEngineOff()
    {
        return True;
    }

    public function HTMLSpecialCharsOff()
    {
        return True;
    }


    public function endforeach(String $dumper)
    {
        $result = "";
        $param = $this->params;
        extract($this->params);
        $terms = $this->terms;
        $exploded_terms = explode("as", $terms);
        if(count($exploded_terms) == 2){
            $exploded_variable = explode("=>", $exploded_terms[1]);
            if(count($exploded_variable) == 2){
                $as_before = ltrim(trim($exploded_terms[0]), "$");
                $key = ltrim(trim($exploded_variable[0]), "$");
                $value = ltrim(trim($exploded_variable[1]), "$");
                foreach($$as_before as $k => $v){
                    $add_array = [
                        $key => $k,
                        $value => $v
                    ];
                    $params = array_merge($param, $add_array);
                    $AnalyzerInstance = new Analyzer();
                    $returned =  $AnalyzerInstance->Main($dumper, $params, False, $this->parsemode, $this->isHTMLSC);
                    if(strpos($returned,'ORTHIASIGNAL@') !== false){
                        $rslt = explode("##", $returned);
                        if(count($rslt) >= 2) {
                            $result .= $rslt[0];
                            $order = explode("@", $rslt[1]);
                            if(count($order) >= 2) {
                                if ($order[1] == "STOP") {
                                    break;
                                } else if ($order[1] == "CONTINUE") {
                                    continue;
                                }else{
                                    break;
                                }
                            }else{
                                $result .= $returned;
                            }
                        }else{
                            $result .= $returned;
                        }
                    }else{
                        $result .= $returned;
                    }
                }
            }else{
                $term0 = ltrim(trim($exploded_terms[0]), "$");
                $term1 = ltrim(trim($exploded_terms[1]), "$");
                foreach($$term0 as $$term1){
                    $params = $this->params;
                    $params[$term1] = $$term1;
                    $AnalyzerInstance = new Analyzer();
                    $result .= $AnalyzerInstance->Main($dumper, $params, False, $this->parsemode, $this->isHTMLSC);
                }
            }
            return $result;
        }else{
            throw new ClearSkyOrthiaException("foreach構文の条件指定が誤っています。");
        }
    }

    public function endfor(String $dumper)
    {
        $param = $this->params;
        $result = "";
        extract($this->params);
        $terms = $this->terms;
        $exploded_terms = explode(";", $terms);
        if(count($exploded_terms) == 3){
            $initializer = explode("=", $exploded_terms[0]);
            $variable_term = $exploded_terms[1];
            $doing = $exploded_terms[2];
            if(count($initializer) == 2){
                $initializer_variable = ltrim(trim($initializer[0]), "$");
                $initializer_value = trim($initializer[1]);
                //TODO 代入されたのが変数または関数である場合
                if(is_numeric($initializer_value) || isset($$initializer_value) && is_int($$initializer_value) || is_int(eval("return ".$$initializer_value.";"))){
                    if(is_numeric($initializer_value)) {
                        $$initializer_variable = (int)$initializer_value;
                    }else if(isset($$initializer_value) && is_int($$initializer_value)){
                        $$initializer_variable = $$initializer_value;
                    }else if(is_int(eval("return ".$$initializer_value.";"))){
                        $$initializer_variable = eval("reuturn ".$$initializer_variable.";");
                    }else{
                        $$intiializer_variable = 0;
                    }
                    while(True){
                        if(eval("return ".$variable_term.";")){
                            break;
                        }else{
                            $add_params = compact($initializer_variable);
                            $params = array_merge($param, $add_params);
                            $AnalyzerInstance = new Analyzer();
                            $returned = $AnalyzerInstance->Main($dumper, $params, False, $this->parsemode, $this->isHTMLSC);
                            if(strpos($returned,'ORTHIASIGNAL@') !== false){
                                $rslt = explode("##", $returned);
                                if(count($rslt) >= 2) {
                                    $result .= $rslt[0];
                                    $order = explode("@", $rslt[1]);
                                    if(count($order) >= 2) {
                                        if ($order[1] == "STOP") {
                                            break;
                                        } else if ($order[1] == "CONTINUE") {
                                            continue;
                                        }else{
                                            break;
                                        }
                                    }else{
                                        $result .= $returned;
                                    }
                                }else{
                                    $result .= $returned;
                                }
                            }else{
                                $result .= $returned;
                            }
                            eval($doing.";");
                        }
                    }
                    return $result;
                }else{
                    throw new ClearSkyOrthiaException("for構文の条件指定が誤っています。");
                }
            }else{
                throw new ClearSkyOrthiaException("for構文の条件指定が誤っています。");
                exit(1);
            }
        }else{
            throw new ClearSkyOrthiaException("for構文の条件指定が誤っています。");
            exit(1);
        }
    }

    public function endif(String $template)
    {
        $params = $this->params;
        $AnalyzerInstance = new Analyzer();
        $returned = $AnalyzerInstance->Main($template, $params, False, $this->parsemode);
        return $returned;
    }

    public function endcomment(String $dumped)
    {
        return "";
    }

    public function endOrthiaRewriteEngineOff(String $dumped)
    {
        return $dumped;
    }

    public function endHTMLSpecialCharsOff(String $dumped)
    {
        $params = $this->params;
        $AnalyzerInstance = new Analyzer();
        $returned = $AnalyzerInstance->Main($dumped, $params, False, $this->parsemode, False);
        return $returned;
    }

    public function endparts_block(String $dumper)
    {
        $params = $this->params;
        $AnalyzerInstance = new Analyzer();
        $returned = $AnalyzerInstance->Main($dumper, $params, False, $this->parsemode, $this->isHTMLSC);
        $this->copy_parts["[ORTHIABLOCKOBJECT]".trim(trim(trim($this->block_name), "'"), '"')] = $returned;
        return "";
    }

    public function assembleTo(String $path)
    {
        $param = $this->params;
        $params = array_merge($param, $this->copy_parts);
        $path = dirname(__FILE__)."/../../../../../Web/Templates/Normal/".trim(trim(trim(trim(trim($path), "/"), "\\"), "'"), '"');
        if(file_exists($path)){
            $frame_template = file_get_contents($path);
            $AnalyzerInstance = new Analyzer();
            $returned = $AnalyzerInstance->Main($frame_template, $params, False, $this->parsemode);
            return $returned;
        }else{
            return "";
        }
    }
}
