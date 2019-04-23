<?php
//   Copyright 2019 NEC Corporation
//
//   Licensed under the Apache License, Version 2.0 (the "License");
//   you may not use this file except in compliance with the License.
//   You may obtain a copy of the License at
//
//       http://www.apache.org/licenses/LICENSE-2.0
//
//   Unless required by applicable law or agreed to in writing, software
//   distributed under the License is distributed on an "AS IS" BASIS,
//   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//   See the License for the specific language governing permissions and
//   limitations under the License.
//
//----ここからクラス定義
class WrappedStringReplaceAdmin{
    protected $strReplacedString;
    
    protected $strHeadPattern;
    protected $strTailPattern;

    //----解析結果を保存する配列
    protected $aryFixedElementFromSourceString;
    protected $aryReplaceElementFromSourceString;
    //解析結果を保存する配列----

    protected $la_aryErrorinfo = array();

    protected $la_aryvarsarray = array();
    
    protected $aryVarsElementFromSourceString;

    function __construct($in_var_heder_id,$strSourceString, $arrylocalvars=array(), $strHeadPattern="{{ ", $strTailPattern=" }}")
    {
        //----デフォルト値の設定
        $this->strReplacedString = '';
        $this->setHeadPattern($strHeadPattern);
        $this->setTailPattern($strTailPattern);
        //デフォルト値の設定----

        //----配列を初期化
        $this->aryFixedElementFromSourceString = array();
        $this->aryReplaceElementFromSourceString = array();

        $this->aryVarsElementFromSourceString = array();

        //配列を初期化----
        
        if( $in_var_heder_id == DF_HOST_VAR_HED){
            // playbook/対話ファイルで使用しているホスト変数取得
            $this->parseWrappedString($in_var_heder_id,$strSourceString,$arrylocalvars);

            // playbookで使用している {% %}で囲まれているホスト変数取得
            $this->getSpecialVARSParsed("BOOK",$strSourceString);
        }
        elseif( $in_var_heder_id == DF_HOST_TPF_HED){
            // playbookで使用しているtemplate変数取得
            $this->serchSRCparamvars($in_var_heder_id,$strSourceString);
        }
        elseif( $in_var_heder_id == DF_HOST_CPF_HED){
            // playbookで使用しているcopyモジュールの変数取得
            $this->parseWrappedString($in_var_heder_id,$strSourceString,$arrylocalvars);
        }
        elseif( $in_var_heder_id == DF_HOST_GBL_HED){
            // playbook/対話ファイルで使用しているホスト変数取得
            $this->parseWrappedString($in_var_heder_id,$strSourceString,$arrylocalvars);

            // playbookで使用している {% %}で囲まれているホスト変数取得
            $this->getSpecialVARSParsed_GBLVARonly("BOOK",$strSourceString);
        }
        elseif( $in_var_heder_id == DF_HOST_TEMP_GBL_HED){
            // テンプレートファイルで使用しているグローバル変数取得
            $this->parseTPFWrappedString(DF_HOST_GBL_HED,$strSourceString,$arrylocalvars);

            // temprateで使用している {% %}で囲まれているグローバル変数取得
            $this->getSpecialVARSParsed_GBLVARonly("TEMP",$strSourceString);
        }
        else{
            // テンプレートファイルで使用しているホスト変数取得
            $this->parseTPFWrappedString(DF_HOST_VAR_HED,$strSourceString,$arrylocalvars);

            // temprateで使用している {% %}で囲まれているホスト変数取得
            $this->getSpecialVARSParsed("TEMP",$strSourceString);
        }
    }
    //----解析用のプロパティ
    function setHeadPattern($strValue){
        $boolRet = false;
        if( is_string($strValue)===true ){
            $this->strHeadPattern = $strValue;
            $boolRet = true;
        }
        return $boolRet;
    }
    function getHeadPattern(){
        return $this->strHeadPattern;
    }
    function setTailPattern($strValue){
        $boolRet = false;
        if( is_string($strValue)===true ){
            $this->strTailPattern = $strValue;
            $boolRet = true;
        }
        return $boolRet;
    }
    function getTailPattern(){
        return $this->strTailPattern;
    }
    //解析用のプロパティ----

    //----解析結果利用のためのプロパティ

    //----解析結果を取得するプロパティ
    function getParsedResult(){
        return array($this->aryFixedElementFromSourceString, $this->aryReplaceElementFromSourceString);
    }
    //解析結果を取得するプロパティ----

    //----置き換え結果取得メソッド
    function getReplacedString(){
        return $this->strReplacedString;
    }
    //置き換え結果取得メソッド----

    //解析結果利用のためのプロパティ----

    //----置き換え用のメソッド
    function stringReplace($in_strSourceString,$in_aryReplaceSource){

        $boolRet = false;

        $strHeadPattern = $this->getHeadPattern();
        $strTailPattern = $this->getTailPattern();

        $this->strReplacedString = "";
        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        foreach($arry_list as $strSourceString){
            $strSourceString = $strSourceString . "\n";
            if(mb_strpos($strSourceString,"#", 0, "UTF-8") === 0){
                $this->strReplacedString = $this->strReplacedString . $strSourceString;
            }
            else{
                // エスケープコード付きの#を一時的に改行に置換
                $wstr = $strSourceString;
                $rpstr  = mb_ereg_replace("\\\\#","\n\n",$wstr);
                // コメント( #)マーク以降の文字列を削除した文字列で変数の具体値置換
                // #の前の文字がスペースの場合にコメントとして扱う
                $expstr = explode(" #",$rpstr);
                $strSourceString = $expstr[0];
                unset($expstr[0]);
                // 各変数を具体値に置換

                foreach($in_aryReplaceSource as $key1=>$val1){
                    $var_name = $strHeadPattern . $key1 . $strTailPattern;
                    // 変数検索
                    if(mb_strpos($strSourceString, $var_name, 0, "UTF-8") === false){
                        continue;
                    }
                    // 変数を具体値に置換
                    $repstr  = mb_ereg_replace($var_name,$val1,$strSourceString);
                    $strSourceString = $repstr;
                }
                // コメント(#)以降の文字列を元に戻す。
                foreach( $expstr as $value ){
                    $strSourceString = $strSourceString . " #" . $value;
                }
                // エスケープコード付きの#を元に戻す。
                $rpstr  = mb_ereg_replace("\n\n","\#",$strSourceString);
                $this->strReplacedString = $this->strReplacedString . $rpstr;
            }            
        }
        return $boolRet;
    }
    //置き換え用のメソッド----


    //----ホスト変数解析用のメソッド
    function parseWrappedString($in_var_heder,$in_strSourceString,$arrylocalvars){
        $boolRet = false;

        //
        $this->aryFixedElementFromSourceString = array();
        $this->aryReplaceElementFromSourceString = array();
        //
        $strHeadPattern = $this->getHeadPattern();
        $strTailPattern = $this->getTailPattern();
        //
        $numLengthOfHeadPattern = mb_strlen($strHeadPattern, "UTF-8");
        $numLengthOfTailPattern = mb_strlen($strTailPattern, "UTF-8");

        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        foreach($arry_list as $strSourceString){
            $strSourceString = $strSourceString . "\n";
            // コメント行は読み飛ばす
            if(mb_strpos($strSourceString,"#",0,"UTF-8") === 0){
                $this->aryFixedElementFromSourceString[] = $strSourceString;
                continue;
            }
            // エスケープコード付きの#を一時的に改行に置換
            $wstr = $strSourceString;
            $rpstr  = mb_ereg_replace("\\\\#","\n\n",$wstr);
            // コメント( #)マーク以降の文字列を削除する。
            // #の前の文字がスペースの場合にコメントとして扱う
            $wspstr = explode(" #",$rpstr);
            $strSourceString = $wspstr[0];
            if( is_string($strSourceString)===true ){
                $boolRet = true;
                $strRemainString = $strSourceString;

                do{
                    $numLengthOfRemainString = mb_strlen($strRemainString, "UTF-8");
                    $numResultOfSearchHead = mb_strpos($strRemainString, $strHeadPattern, 0,"UTF-8");
                    if( $numResultOfSearchHead===false ){
                        //----先頭パターンが見つからなかった
                        $this->aryFixedElementFromSourceString[] = $strRemainString;
                        break;
                        //先頭パターンが見つからなかった----
                    }else{
                        $strTempStr1Body = mb_substr($strRemainString, 0, $numResultOfSearchHead);

                        $strTempRemainString = mb_substr($strRemainString, $numResultOfSearchHead + $numLengthOfHeadPattern, $numLengthOfRemainString - $numResultOfSearchHead - $numLengthOfHeadPattern, "UTF-8");
                        $numResultOfSearchTail = mb_strpos($strTempRemainString, $strTailPattern, 0, "UTF-8");
                        if( $numResultOfSearchTail===false ){
                            //----末尾パターンが見つからなかった
                            $this->aryFixedElementFromSourceString[] = $strRemainString;
                            break;
                            //末尾パターンが見つからなかった----
                        }else{
                            $this->aryFixedElementFromSourceString[] = $strTempStr1Body;

                            $strWrappedString = mb_substr($strTempRemainString, 0, $numResultOfSearchTail, "UTF-8");
                            //ローカル予約変数か判定する。
                            foreach( $arrylocalvars as $lvarname ){
                                if($strWrappedString == $lvarname){
                                    //変数名を退避する
                                    $this->aryReplaceElementFromSourceString[] = $strWrappedString;
                                }
                            }
                            //変数名の先頭がユーザー変数を表す文字列となっているか判定
                            if(mb_strpos($strWrappedString,$in_var_heder,0,"UTF-8")===0){
                                // 変数名が英数字と_かチェック これ以外の場合は変数として扱わない
                                $strWrappedString = trim($strWrappedString);
                                $ret = preg_match("/^" . $in_var_heder . "[a-zA-Z0-9_]*$/",$strWrappedString);
                                if($ret === 1){
                                    //変数名を退避する
                                    $this->aryReplaceElementFromSourceString[] = $strWrappedString;
                                }
                            }

                            $numLengthOfTempRemainString = mb_strlen($strTempRemainString, "UTF-8");
                            $strRemainString = mb_substr($strTempRemainString, $numResultOfSearchTail + $numLengthOfTailPattern, $numLengthOfTempRemainString - $numResultOfSearchTail - $numLengthOfTailPattern, "UTF-8");
                        }
                    }
                }while( $numResultOfSearchHead!==false && $numResultOfSearchTail!==false );
            }
        }
        return $boolRet;
    }
    //解析用のメソッド----

    //----template変数解析結果を取得するプロパティ
    function getTPFvarsarrayResult(){
        return array($this->la_aryvarsarray,$this->la_aryErrorinfo);
    }
    //template解析結果を取得するプロパティ----

    //----copy変数解析結果を取得するプロパティ
    function getCPFvarsarrayResult(){
        return array($this->la_aryvarsarray,$this->la_aryErrorinfo);
    }
    //copy解析結果を取得するプロパティ----

    ////////////////////////////////////////////////////////////////////////
    // $in_param_name:   パラメータ名
    // $ina_flg_list:    区切文字配列　　　　区切文字: / または : の配列
    //                   [区切文字]=1
    // $in_var_heder_id: 変数名の先頭文字列　TPF_
    // $in_str:          playbookの内容(1行)
    // $in_TPFvarname:   取得した変数名格納
    // $in_var_only_chk: 変数定義チェックモード　defalut=false
    //                   true: 変数名のみ　false:パラメータと変数のチェック
    ////////////////////////////////////////////////////////////////////////
    function gethostvarname($in_param_name,$ina_flg_list,$in_var_heder_id,$in_str,&$in_TPFvarname,
                            $in_var_only_chk=false){
        $in_TPFvarname = "";
        $var_name_key  = $in_var_heder_id . '[a-zA-Z0-9_]*';
        $var_key       =       '{{(\s)' . $var_name_key . '(\s)}}';
        $value_key     = '(\'|"){{(\s)' . $var_name_key . '(\s)}}(\'|")';

        // 変数定義チェックモードの場合
        if($in_var_only_chk === true){
            // 変数名が定義されているか判定
            $ret = preg_match_all('/'. $var_key . '/',$in_str,$var_match);
            if($ret != 1){
                // 変数を使用していない
                return -3;
            }
            $ret = preg_match_all('/'. $value_key . '/',$in_str,$var_match);
            if($ret != 1){
                 // 構文が不正
                 return -4;
            }
            // 変数名を抜く
            $ret = preg_match_all('/'. $var_name_key . '/',$var_match[0][0],$var_match);
            if($ret != 1){
                // 変数名が不正
                return 100;
            }
            $in_TPFvarname = $var_match[0][0];
            return 0;
        }
        // '{{ xxxxxxx }}'をOKにする。
        foreach($ina_flg_list as $flg=>$dummy){
            // 区切文字が = か : を判別
            if($flg == "="){
                $var_key = '(\'|"|){{(\s)' . $var_name_key . '(\s)}}(\'|"|)';
                $param_key = $in_param_name . $flg;
                // param=value   =の前後のスペースは構文エラー
                $param_value = $param_key . "(\S)";
                $sarch_key = $param_key . $var_key;
                $param_only_key = "";
                $about_sarch_key = "";
            }
            else if($flg == ":"){
                // :の場合は変数を'または"で囲む
                $var_key = '(\'|"){{(\s)' . $var_name_key . '(\s)}}(\'|")';
                // src:{{ xxxxx }} はNG
                $param_key = '^(\s*)' . $in_param_name . '(\s*)' . $flg;
                // params:(\s+)value
                $param_value = $param_key . "(\s+)(\S)"; 
                $param_not_value = $param_key . "(\s*)$"; 
                $param_only_key = '^(\s*)' . $in_param_name . '(\s*)' . $flg . '(\s*)$';
                $sarch_key = $param_key . "(\s+)" . $var_key;
                $about_sarch_key = $param_key . "(\s+)" . '{{(\s)' . $var_name_key . '(\s)}}';
            }
            else{
                continue;
            }
            // src:
            //     {{ xxxxx }} 
            // の場合のチェック
            if(strlen($param_only_key)!=0){
                // パラメータのみ定義か判定
                $ret = preg_match_all('/' . $param_only_key . '/',trim($in_str),$var_match);
                if($ret == 1){
                    // パラメータのみ定義
                    return -2;
                }
            }
            // パラメータが定義されているか
            $ret = preg_match_all('/' . $param_key . '/',$in_str,$var_match);
            if($ret == 0){
                // 次の形式へ
                continue;
            }
            // パラメータ値が設定されているか
            $ret = preg_match_all('/' . $param_value . '/',$in_str,$var_match);
            if($ret == 0){
                // 区切文字が : を判別
                // :の場合はパラメータ値の定義が構文エラーか判定
                if($flg == ":"){
                    // param:(\s+)valueでない場合は構文エラー
                    $ret = preg_match_all('/' . $param_not_value . '/',$in_str,$var_match);
                    if($ret == 0){
                        // 構文が不正
                        return -4;
                    }
                }
                // 区切文字が = を判別
                // =の場合はパラメータ値がないのは構文エラー
                if($flg == "="){
                    // 構文が不正
                    return -4;
                }
                // 次の形式へ
                continue;
            }
            // パラメータと変数が定義されているか
            $ret = preg_match_all('/' . $sarch_key . '/',$in_str,$var_match);
            if($ret != 1){
                if(strlen($about_sarch_key)!=0){
                    // 変数定義の不備を確認
                    $ret = preg_match_all('/' . $about_sarch_key . '/',$in_str,$var_match);
                    if($ret != 0){
                        // 構文が不正
                        return -4;
                    }
                }
                // 変数を使用していない
                return -3;
            }
            // 変数名を抜く
            $ret = preg_match_all('/'. $var_name_key . '/',$var_match[0][0],$var_match);
            if($ret != 1){
                // 変数名が不正
                return 100;
            }
            $in_TPFvarname = $var_match[0][0];
            return 0;

        }
        // パラメータ定義なし
        return -1;
    }

    //----テンプレートファイル内のホスト変数解析用のメソッド
    function parseTPFWrappedString($in_var_heder,$in_strSourceString,$arrylocalvars){
        $boolRet = false;

        $this->aryVarsElementFromSourceString = array();

        $strHeadPattern = $this->getHeadPattern();
        $strTailPattern = $this->getTailPattern();
        //
        $numLengthOfHeadPattern = mb_strlen($strHeadPattern, "UTF-8");
        $numLengthOfTailPattern = mb_strlen($strTailPattern, "UTF-8");

        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        foreach($arry_list as $strSourceString){
            if( is_string($strSourceString)===true ){
                $boolRet = true;
                $strRemainString = $strSourceString;

                do{
                    $numLengthOfRemainString = mb_strlen($strRemainString, "UTF-8");
                    $numResultOfSearchHead = mb_strpos($strRemainString, $strHeadPattern, 0,"UTF-8");
                    if( $numResultOfSearchHead===false ){
                        //----先頭パターンが見つからなかった
                        break;
                        //先頭パターンが見つからなかった----
                    }else{
                        $strTempStr1Body = mb_substr($strRemainString, 0, $numResultOfSearchHead);

                        $strTempRemainString = mb_substr($strRemainString, $numResultOfSearchHead + $numLengthOfHeadPattern, $numLengthOfRemainString - $numResultOfSearchHead - $numLengthOfHeadPattern, "UTF-8");
                        $numResultOfSearchTail = mb_strpos($strTempRemainString, $strTailPattern, 0, "UTF-8");
                        if( $numResultOfSearchTail===false ){
                            //----末尾パターンが見つからなかった
                            break;
                            //末尾パターンが見つからなかった----
                        }else{
                            $strWrappedString = mb_substr($strTempRemainString, 0, $numResultOfSearchTail, "UTF-8");
                            //ローカル予約変数か判定する。
                            foreach( $arrylocalvars as $lvarname ){
                                if($strWrappedString == $lvarname){
                                    //変数名を退避する
                                    $this->aryVarsElementFromSourceString[] = $strWrappedString;
                                }
                            }
                            //変数名の先頭がユーザー変数を表す文字列となっているか判定
                            if(mb_strpos($strWrappedString,$in_var_heder,0,"UTF-8")===0){
                                // #1081 2016/11/02 Update start
                                // 変数名が英数字と_かチェック これ以外の場合は変数として扱わない
                                $strWrappedString= trim($strWrappedString);
                                $ret = preg_match("/^" . $in_var_heder . "[a-zA-Z0-9_]*$/",$strWrappedString);
                                if($ret === 1){
                                    //変数名を退避する
                                    $this->aryVarsElementFromSourceString[] = $strWrappedString;
                                }
                            }

                            $numLengthOfTempRemainString = mb_strlen($strTempRemainString, "UTF-8");
                            $strRemainString = mb_substr($strTempRemainString, $numResultOfSearchTail + $numLengthOfTailPattern, $numLengthOfTempRemainString - $numResultOfSearchTail - $numLengthOfTailPattern, "UTF-8");
                        }
                    }
                }while( $numResultOfSearchHead!==false && $numResultOfSearchTail!==false );
            }
        }
        return $boolRet;
    }
    //解析用のメソッド----

    //----テンプレートファイル内のホスト変数解析結果を取得するプロパティ
    function getTPFVARSParsedResult(){
        return $this->aryVarsElementFromSourceString;
    }
    //解析結果を取得するプロパティ----
    function getSpecialVARSParsed($in_type,$in_strSourceString){
        $strChkString = "";

        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        foreach($arry_list as $strSourceString){
            if($in_type == "BOOK"){
                // Playbookの場合はコメント行は読み飛ばす
                if(mb_strpos($strSourceString,"#",0,"UTF-8") === 0){
                    continue;
                }
                // コメント( #)マーク以降の文字列を削除する。
                // #の前の文字がスペースの場合にコメントとして扱う
                $wspstr = explode(" #",$strSourceString);
                $strRemainString = $wspstr[0];
                if( is_string($strRemainString)===true ){
                    $strChkString = $strChkString . $strRemainString;
                }
            }
            else{
                // temprateの場合
                $strChkString = $strChkString . $strSourceString;
            }
        }
        // 改行をスペースにする 制御コードを取除く
        $strChkString = preg_replace("/\n/"," ",$strChkString);
        // tabをスペースにする  制御コードを取除く
        $strChkString = preg_replace("/\t/"," ",$strChkString);
        // {% %}で囲まれている文字列を検索
        $ret = preg_match_all("/{%.+?%}/",$strChkString,$match);
        if($ret !== false){
            // {% %}で囲まれている文字列から変数定義を抜出す
            for($idx1=0;$idx1 < count($match[0]);$idx1++){
                // 変数名　△VAR_xxxx△ を取出す   xxxx::半角英数字か__
                $ret = preg_match_all("/(\s)VAR_[a-zA-Z0-9_]*(\s)/",$match[0][$idx1],$var_match);
                if($ret !== false){
                    for($idx2=0;$idx2 < count($var_match[0]);$idx2++){
                        // playbookかtemprateの判定
                        if($in_type == "BOOK"){
                            //playbookの変数名を退避する
                            $this->aryReplaceElementFromSourceString[] = trim($var_match[0][$idx2]);
                        }
                        else{
                            //temprateの変数名を退避する
                            $this->aryVarsElementFromSourceString[] = trim($var_match[0][$idx2]);
                        }
                    }
                }
            }
        }
    }
    function getIndentPos($in_String){
        if(strlen($in_String)===0){
            return -1;
        }
        $space_count=0;
        $read_array = str_split($in_String);
        for($idx=0;$idx<count($read_array);$idx++){
            // - もスペースとしてカウント
            if($read_array[$idx] == " " || $read_array[$idx] == "-"){
               $space_count++;
            }
            else{
                break;
            }
        }
        return $space_count;
    }
    //----PlayBookのtemplate/copyモジュールのsrcパラメータの変数数解析用のメソッド
    function serchSRCparamvars($in_var_heder_id,$in_strSourceString){
        $boolRet = true;

        $this->la_aryErrorinfo = array();
        $this->la_aryvarsarray = array();

        if( $in_var_heder_id == DF_HOST_TPF_HED){
            $errmsg_ptn_1 = "ITAANSTWRH-ERR-90095";
            $errmsg_ptn_2 = "ITAANSTWRH-ERR-90096";
            $errmsg_ptn_3 = "ITAANSTWRH-ERR-90097";
            $module       = "template:";
        }
        if( $in_var_heder_id == DF_HOST_CPF_HED){
            $errmsg_ptn_1 = "ITAANSTWRH-ERR-90088";
            $errmsg_ptn_2 = "ITAANSTWRH-ERR-90089";
            $errmsg_ptn_3 = "ITAANSTWRH-ERR-90094";
            $module       = "copy:";
        }

        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        // 0:複数行でない　1:複数行　2:複数行で変数が別の行に定義
        $next_line_search = 0;  
        $line = 0;
        $tpf_line = 0;

        $temp_indentpos = 0; // モジュール定義のインデントの数
        $param_name     = 'src';
        $flg_arry_iko = array('='=>1);
        $flg_arry_con = array(':'=>1);
        $flg_arry = array();

        foreach($arry_list as $strSourceString){
            // 行番号
            $line = $line + 1;

            $strSourceString = $strSourceString . "\n";
            // コメント行は読み飛ばす
            if(mb_strpos($strSourceString,"#",0,"UTF-8") === 0){
                continue;
            }
            // エスケープコード付きの#を一時的に改行に置換
            $wstr = $strSourceString;
            // コメント( #)マーク以降の文字列を削除する。
            // #の前の文字がスペースの場合にコメントとして扱う
            $wspstr = explode(" #",$wstr);
            $strRemainString = $wspstr[0];
            if( is_string($strRemainString)===true ){
                //空行は読み飛ばす
                if(strlen(trim($strRemainString)) == 0){
                    continue;
                }
                // ↓cモジュールが複数行またがりのケース
                if($next_line_search != 0){
                    // モジュールが複数行またがり「あり」パラメータのみ定義　変数は次の行の場合
                    if($next_line_search == 2){
                        // 次のモジュールかパラメータがある場合
                        if(preg_match("/(\S+):(\s+)/",$strRemainString,$val) === 1){
                            // srcパラメータがないのに次のモジュールの定義がされている
                            $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097
                            // モジュールが複数行またがり「なし」
                            $next_line_search = 0;
                            $boolRet = false;
                        }
                        else{
                            $CPFvarsarray = "";
                            // srcパラメータの変数名のサーチ
                            $ret = $this->gethostvarname("",
                                                     $flg_arry,      // #1233 2017/07/20 Update
                                                     $in_var_heder_id,
                                                     $strRemainString,
                                                     $CPFvarsarray,
                                                     true);   //変数名のチェックのみ
                            switch($ret){
                            // 変数名取得
                            case 0:
                                // 変数名退避
                                $this->la_aryvarsarray[$tpf_line] = $CPFvarsarray;
    
                                // モジュールが複数行またがり「なし」
                                $next_line_search = 0;
                                break;
                            // 変数名未定義
                            case 100:
                                // エラー情報退避
                                $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_2;  //ITAANSTWRH-ERR-90089/ITAANSTWRH-ERR-90096
                                // モジュールが複数行またがり「なし」
                                $next_line_search = 0;
                                $boolRet = false;
                                break;
                            case -3:
                                // 埋め込み変数未使用
                                // 次の行へ
                                $next_line_search = 0;
                                break;
                            case -4:
                                // 構文エラー パラメータ値なし
                                // エラー情報退避
                                $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097
        
                                $next_line_search = 0;
                                $boolRet = false;
                                break;
                            }
                            // 次の行へ
                            continue;
                        }
                    }
                    // srcパラメータがないのに次のモジュールの定義されている場合
                    if(preg_match("/(\S+):(\s+)/",$strRemainString,$val) === 1){
                        // インデントの数を数える
                        $indentpos = $this->getIndentPos($strRemainString);
                        // copy:
                        // xxxxxxxx:   => ng(次のモジュール定義)
                        // copyと同じインデントの場合は、次のモジュールの定義と判定する。
                        if($indentpos <= $temp_indentpos){
                            // srcパラメータがないのに次のモジュールの定義がされている
                            $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_1;  //ITAANSTWRH-ERR-90088/ITAANSTWRH-ERR-90095

                            // モジュールが複数行またがり「なし」
                            $next_line_search = 0;

                            $boolRet = false;
                        }
                        else{
                            $CPFvarsarray = "";
                            // srcパラメータ変数名サーチの変数名サーチ
                            $ret = $this->gethostvarname($param_name,
                                                  $flg_arry,
                                                  $in_var_heder_id,
                                                  $strRemainString,
                                                  $CPFvarsarray);
                            switch($ret){
                            // 変数名取得
                            case 0:
                                // 変数名退避
                                $this->la_aryvarsarray[$tpf_line] = $CPFvarsarray;
                                // モジュールが複数行またがり「なし」
                                $next_line_search = 0;
                                break;
                            // 変数名不正
                            case 100:
                                // エラー情報退避
                                $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_2;  //ITAANSTWRH-ERR-90089/ITAANSTWRH-ERR-90096
                                // モジュールが複数行またがり「なし」
                                $next_line_search = 0;
    
                                $boolRet = false;
    
                                break;
                            // 該当パラメータなし
                            case -1:
                                // モジュールが複数行またがり「あり」
                                $next_line_search = 1;
                                break;
                            // パラメータのみ定義　変数は次の行
                            case -2:
                                // モジュールが複数行またがり「あり」パラメータのみ定義　変数は次の行
                                $next_line_search = 2;
                                break;
                            case -3:
                                // 埋め込み変数未使用
                                // 次の行へ
                                $next_line_search = 0;
                                break;
                            case -4:
                                // 構文エラー パラメータ値なし
                                // エラー情報退避
        
                                $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097
        
                                $next_line_search = 0;
                                $boolRet = false;
                                break;
                            }
                        }
                    }
                    else{
                        $CPFvarsarray = "";
                        // srcパラメータ変数名サーチの変数名サーチ
                        $ret = $this->gethostvarname($param_name,
                                              $flg_arry,
                                              $in_var_heder_id,
                                              $strRemainString,
                                              $CPFvarsarray);
                        switch($ret){
                        // 変数名取得
                        case 0:
                            // 変数名退避
                            $this->la_aryvarsarray[$tpf_line] = $CPFvarsarray;
                            // モジュールが複数行またがり「なし」
                            $next_line_search = 0;
                            break;
                        // 変数名不正
                        case 100:
                            // エラー情報退避
                            $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_2;  //ITAANSTWRH-ERR-90089/ITAANSTWRH-ERR-90096
                            // モジュールが複数行またがり「なし」
                            $next_line_search = 0;

                            $boolRet = false;

                            break;
                        // 該当パラメータなし
                        case -1:
                            // モジュールが複数行またがり「あり」
                            $next_line_search = 1;
                            break;
                        // パラメータのみ定義　変数は次の行
                        case -2:
                            // モジュールが複数行またがり「あり」パラメータのみ定義　変数は次の行
                            $next_line_search = 2;
                            break;
                        case -3:
                            // 埋め込み変数未使用
                            // 次の行へ
                            $next_line_search = 0;
                            break;
                        case -4:
                            // 構文エラー パラメータ値なし
                            // エラー情報退避
    
                            $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097
    
                            $next_line_search = 0;
                            $boolRet = false;
                            break;
                        }
                    }
                }
                //↑複数行またがりのケース
                
                // モジュールが定義されているか判定
                $toppos = mb_strpos($strRemainString, $module, 0,"UTF-8");
                if($toppos !== false){
                    $flg_arry = array();
                    // モジュール: >の定義か判定
                    $ret = preg_match_all('/' . $module . "(\s*)>" . '/',$strRemainString,$var_match);
                    if($ret != 0){
                        // 区切り =
                        $flg_arry = $flg_arry_iko;
                    }
                    else{
                        // モジュール: の定義か判定
                        $ret = preg_match_all('/' . $module . '$/',trim($strRemainString),$var_match);
                        if($ret != 0){
                            // 区切り :
                            $flg_arry = $flg_arry_con;
                        }
                        else{
                            // 区切り =
                            $flg_arry = $flg_arry_iko;
                        }
                    }

                    // インデントの数を数える
                    $temp_indentpos = $this->getIndentPos($strRemainString);
   
                    // モジュールが定義されている行番号退避
                    $tpf_line     = $line;
                    $CPFvarsarray = "";
                    // copy: src=xxxxx の場合のsrcパラメータの有無をチェック
                    // copy: src:は文法エラー
                    $ret = $this->gethostvarname($param_name,
                                          $flg_arry,
                                          $in_var_heder_id,
                                          $strRemainString,
                                          $CPFvarsarray);
                    switch($ret){
                    // 変数名取得
                    case 0:
                        // 変数名退避
                        $this->la_aryvarsarray[$tpf_line] = $CPFvarsarray;
                        // モジュールが複数行またがり「なし」
                        $next_line_search = 0;

                        // 次の行へ
                        break;
                    // 変数名不正
                    case 100:
                        // エラー情報退避
                        $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_2;  //ITAANSTWRH-ERR-90089/ITAANSTWRH-ERR-90096
                        // モジュールが複数行またがり「なし」
                        $next_line_search = 0;

                        $boolRet = false;

                        break;
                    // 該当パラメータなし
                    case -1:
                        // モジュールが複数行またがり「あり」
                        $next_line_search = 1;
                        // 次の行へ
                        break;
                    // パラメータのみ定義　変数は次の行
                    case -2:
                        // モジュールが複数行またがり「あり」パラメータのみ定義　変数は次の行
                        $next_line_search = 2;
                        break;
                    case -3:
                        // 埋め込み変数未使用
                        // 次の行へ
                        $next_line_search = 0;
                        break;
                    case -4:
                        // 構文エラー パラメータ値なし
                        // エラー情報退避

                        $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097

                        $next_line_search = 0;
                        $boolRet = false;
                        break;
                    }
                }
            }
        }
        // モジュールが複数行またがり「あり」で終わっているのでエラー
        if($next_line_search != 0){
            // srcパラメータがないか、srcパラメータの値がないのにデータが終わっている

            $this->la_aryErrorinfo[$tpf_line]=$errmsg_ptn_3;  //ITAANSTWRH-ERR-90094/ITAANSTWRH-ERR-90097
            $boolRet = false;
        }
        return $boolRet;
    }
    function getSpecialVARSParsed_GBLVARonly($in_type,$in_strSourceString){
        $strChkString = "";

        // 入力データを行単位に分解
        $arry_list = explode("\n",$in_strSourceString);
        foreach($arry_list as $strSourceString){
            if($in_type == "BOOK"){
                // Playbookの場合はコメント行は読み飛ばす
                if(mb_strpos($strSourceString,"#",0,"UTF-8") === 0){
                    continue;
                }
                // コメント( #)マーク以降の文字列を削除する。
                // #の前の文字がスペースの場合にコメントとして扱う
                $wspstr = explode(" #",$strSourceString);
                $strRemainString = $wspstr[0];
                if( is_string($strRemainString)===true ){
                    $strChkString = $strChkString . $strRemainString;
                }
            }
            else{
                // temprateの場合
                $strChkString = $strChkString . $strSourceString;
            }
        }
        // 改行をスペースにする 制御コードを取除く
        $strChkString = preg_replace("/\n/"," ",$strChkString);
        // tabをスペースにする  制御コードを取除く
        $strChkString = preg_replace("/\t/"," ",$strChkString);
        // {% %}で囲まれている文字列を検索
        $ret = preg_match_all("/{%.+?%}/",$strChkString,$match);
        if($ret !== false){
            // {% %}で囲まれている文字列から変数定義を抜出す
            for($idx1=0;$idx1 < count($match[0]);$idx1++){
                // 変数名　△VAR_xxxx△ を取出す   xxxx::半角英数字か__
                $ret = preg_match_all("/(\s)GBL_[a-zA-Z0-9_]*(\s)/",$match[0][$idx1],$var_match);
                if($ret !== false){
                    for($idx2=0;$idx2 < count($var_match[0]);$idx2++){
                        // playbookかtemprateの判定
                        if($in_type == "BOOK"){
                            //playbookの変数名を退避する
                            $this->aryReplaceElementFromSourceString[] = trim($var_match[0][$idx2]);
                        }
                        else{
                            //temprateの変数名を退避する
                            $this->aryVarsElementFromSourceString[] = trim($var_match[0][$idx2]);
                        }
                    }
                }
            }
        }
    }
}
//----ここまでクラス定義

////////////////////////////////////////////////////////////////////////
// 概要
//   指定された文字列から変数を抜出す。
// パラメータ
//   $in_var_heder_id:    変数名の先頭文字列　TPF_
//   $in_strSourceString: ファイルの内容
//   $ina_varsarray:      取得した変数配列
//                        $ina_varsarray[][行番号][変数名]
// 戻り値
//   true
////////////////////////////////////////////////////////////////////////
function SimpleVerSearch($in_var_heder_id,$in_strSourceString,&$ina_varsarray){
    $ina_varsarray = array();
    // 入力データを行単位に分解
    $arry_list = explode("\n",$in_strSourceString);
    $line = 0;
    foreach($arry_list as $strSourceString){
        // 行番号
        $line = $line + 1;

        $strSourceString = $strSourceString . "\n";
        // コメント行は読み飛ばす
        if(mb_strpos($strSourceString,"#",0,"UTF-8") === 0){
            continue;
        }
        // エスケープコード付きの#を一時的に改行に置換
        $wstr = $strSourceString;
        // コメント( #)マーク以降の文字列を削除する。
        // #の前の文字がスペースの場合にコメントとして扱う
        $wspstr = explode(" #",$wstr);
        $strRemainString = $wspstr[0];
        if( is_string($strRemainString)===true ){
            //空行は読み飛ばす
            if(strlen(trim($strRemainString)) == 0){
                continue;
            }
            // 変数名　{{ ???_[a-zA-Z0-9_] }} を取出す
            $ret = preg_match_all("/{{(\s)" . $in_var_heder_id . "[a-zA-Z0-9_]*(\s)}}/",$strRemainString,$var_match);
            if($ret !== false){
                for($idx2=0;$idx2 < count($var_match[0]);$idx2++){
                    //変数名を退避する
                    $array = array();
                    $ret = preg_match_all("/" . $in_var_heder_id . "[a-zA-Z0-9_]*/",$var_match[0][$idx2],$var_name_match);
                    $array[$line] = trim($var_name_match[0][0]);
                    $ina_varsarray[] = $array;
                }
            }
        }
    }
    return true;
}

?>
