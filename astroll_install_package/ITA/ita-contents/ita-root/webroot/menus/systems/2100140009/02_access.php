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
//////////////////////////////////////////////////////////////////////
//
//  【処理概要】
//	・WebDBCore機能を用いたWebページの、動的再描画などを行う。
//
//////////////////////////////////////////////////////////////////////

/* ディレクトリパスの取得 */
$tmpAry=explode('ita-root', dirname(__FILE__));$root_dir_path=$tmpAry[0].'ita-root';unset($tmpAry);
/* 共通パーツ（web_parts_for_template_02_access.php）の読み込み */
require_once ( $root_dir_path . "/libs/webcommonlibs/table_control_agent/web_parts_for_template_02_access.php");

class Db_Access extends Db_Access_Core {
	/* <START> メニューID（画面上で「メニューグループ：メニュー」の値をキーに他カラムの値を動的に取得する----------------------------------- */
	function Mix1_1_menu_upd($strMenuNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('MENU_ID'=>$strMenuNumeric);

		/* 動的値を取得する対象カラム（項目） */
		$int_seq_no = 2;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	
	function Mix2_1_menu_reg($strMenuNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('MENU_ID'=>$strMenuNumeric);

		/* 動的値を取得する対象カラム（項目） */
		$int_seq_no = 2 ;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果の判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}
		return makeAjaxProxyResultStream($arrayResult);
	}
	/* < END > メニューID（画面上で「メニューグループ：メニュー」の値をキーに他カラムの値を動的に取得する----------------------------------- */

	/* <START> Movementの値をキーに他カラムの値を動的に取得する----------------------------------------------------------------------------- */
	function Mix1_1_pattern_upd($strPatternNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('PATTERN_ID'=>$strPatternNumeric);

		/* 動的値を取得する対象カラム（Key変数名） */
		$int_seq_no = 5 ;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 動的値を取得する対象カラム（Value変数名） */
		$int_seq_no = 8;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult02 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if(( $arrayResult01[0]=="000" ) && ( $arrayResult02[0]=="000" )) {
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3]));
			$strResult02Stream = makeAjaxProxyResultStream(array($arrayResult02[2],$arrayResult02[3]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream,$strResult02Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}
		return makeAjaxProxyResultStream($arrayResult);
	}
	
	function Mix2_1_pattern_reg($strPatternNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('PATTERN_ID'=>$strPatternNumeric);
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");

		/* 動的値を取得する対象カラム（key変数名） */
		$int_seq_no = 5 ;
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 動的値を取得する対象カラム（Value変数名） */
		$int_seq_no = 8;
		$arrayResult02 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if(( $arrayResult01[0]=="000" ) && ( $arrayResult02[0]=="000" )){
			$strResultCode = "000";
			$strDetailCode = "000";

			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3]));
			$strResult02Stream = makeAjaxProxyResultStream(array($arrayResult02[2],$arrayResult02[3]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream,$strResult02Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	/* < END > Movementの値をキーに他カラムの値を動的に取得する----------------------------------------------------------------------------- */

	/* <START> Key変数名の値をキーに他カラムの値を動的に取得する---------------------------------------------------------------------------- */
	function Mix1_1_key_vars_upd($strVarsLinkIdNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('KEY_VARS_LINK_ID'=>$strVarsLinkIdNumeric, 'KEY_NESTEDMEM_COL_CMB_ID' => "");

		/* 動的値を取得する対象カラム（Key：メンバー変数名） */
		$int_seq_no = 6;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	
	function Mix2_1_key_vars_reg($strVarsLinkIdNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('KEY_VARS_LINK_ID'=>$strVarsLinkIdNumeric, 'KEY_NESTEDMEM_COL_CMB_ID' => "");

		/* 動的値を取得する対象カラム（Key：メンバー変数名） */
		$int_seq_no = 6;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	/* < END > Key変数名の値をキーに他カラムの値を動的に取得する---------------------------------------------------------------------------- */

	/* <START> Value変数名の値をキーに他カラムの値を動的に取得する-------------------------------------------------------------------------- */
	function Mix1_1_vars_upd($strVarsLinkIdNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('VALUE_VARS_LINK_ID'=>$strVarsLinkIdNumeric, 'VALUE_NESTEDMEM_COL_CMB_ID' => "");

		/* 動的値を取得する対象カラム（Value：メンバー変数名） */
		$int_seq_no = 9;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}
		return makeAjaxProxyResultStream($arrayResult);
	}
	
	function Mix2_1_vars_reg($strVarsLinkIdNumeric){
		/* グローバル変数宣言 */
		global $g;

		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('VALUE_VARS_LINK_ID'=>$strVarsLinkIdNumeric, 'VALUE_NESTEDMEM_COL_CMB_ID' => "");

		/* 動的値を取得する対象カラム（Value：メンバー変数名） */
		$int_seq_no = 9;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}
		return makeAjaxProxyResultStream($arrayResult);
	}
	/* < END > Value変数名の値をキーに他カラムの値を動的に取得する-------------------------------------------------------------------------- */

	/* <START> メンバー変数名--------------------------------------------------------------------------------------------------------------- */
	function Mix1_1_val_chlVar_upd($objVarID, $objChlVarID){
		/* グローバル変数宣言 */
		global $g;
		
		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('VALUE_VARS_LINK_ID'=>$objVarID, 'VALUE_NESTEDMEM_COL_CMB_ID' => $objChlVarID);

		/* メンバー変数名用 */
		$int_seq_no = 9;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	function Mix2_1_val_chlVar_reg($objVarID, $objChlVarID){
		/* グローバル変数宣言 */
		global $g;
		
		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('VALUE_VARS_LINK_ID'=>$objVarID, 'VALUE_NESTEDMEM_COL_CMB_ID' => $objChlVarID);

		/* メンバー変数名用 */
		$int_seq_no = 9;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}

	function Mix1_1_key_chlVar_upd($objVarID, $objChlVarID){
		/* グローバル変数宣言 */
		global $g;
		
		/* ローカル変数宣言 */
		$aryOverride = array("Mix1_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('KEY_VARS_LINK_ID'=>$objVarID, 'KEY_NESTEDMEM_COL_CMB_ID' => $objChlVarID);

		/* メンバー変数名用 */
		$int_seq_no = 6;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "update_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}

	function Mix2_1_key_chlVar_reg($objVarID, $objChlVarID){
		/* グローバル変数宣言 */
		global $g;
		
		/* ローカル変数宣言 */
		$aryOverride = array("Mix2_1");
		
		$arrayResult = array();
		$aryVariant = array();
		$arySetting = array();

		$strResultCode = "";
		$strDetailCode = "";
		$strOutputStream = "";

		$objTable = loadTable();

		/* 本体ロジックをコール */
		$aryVariant = array('KEY_VARS_LINK_ID'=>$objVarID, 'KEY_NESTEDMEM_COL_CMB_ID' => $objChlVarID);

		/* メンバー変数名用 */
		$int_seq_no = 6;
		require_once ( $g['root_dir_path'] . "/libs/webcommonlibs/table_control_agent/12_singleRowTable_AddSelectTag.php");
		$arrayResult01 = AddSelectTagToDynamicSelectTab($objTable, "register_table", $int_seq_no, $aryVariant, $arySetting, $aryOverride);

		/* 結果判定 */
		if( $arrayResult01[0]=="000" ){
			$strResultCode = "000";
			$strDetailCode = "000";
			$strResult01Stream = makeAjaxProxyResultStream(array($arrayResult01[2],$arrayResult01[3],$arrayResult01[4]));
			$strOutputStream = makeAjaxProxyResultStream(array($strResult01Stream));
		}else{
			$strResultCode = "500";
			$strDetailCode = "000";
		}
		$arrayResult = array($strResultCode,$strDetailCode,$strOutputStream);

		if($arrayResult[0]=="000"){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-STD-4001",__FUNCTION__));
		}else if(intval($arrayResult[0])<500){
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4002",__FUNCTION__));
		}else{
			web_log( $g['objMTS']->getSomeMessage("ITAWDCH-ERR-4001",__FUNCTION__));
		}

		return makeAjaxProxyResultStream($arrayResult);
	}
	/* < END > メンバー変数名--------------------------------------------------------------------------------------------------------------- */

/* < END > サイト個別PHP実装---------------------------------------------------------------------------------------------------------------- */}

$server = new HTML_AJAX_Server();
$server->registerClass(new Db_Access());
$server->handleRequest();
?>
