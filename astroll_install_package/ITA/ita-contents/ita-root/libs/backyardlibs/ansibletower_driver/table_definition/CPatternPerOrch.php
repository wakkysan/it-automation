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
//  【概要】
//      Movement一覧 テーブル定義クラス
//
//////////////////////////////////////////////////////////////////////

////////////////////////////////
// ルートディレクトリを取得
////////////////////////////////
if(empty($root_dir_path)) {
    $root_dir_temp = array();
    $root_dir_temp = explode("ita-root", dirname(__FILE__));
    $root_dir_path = $root_dir_temp[0] . "ita-root";
}

require_once($root_dir_path . "/libs/backyardlibs/ansibletower_driver/table_definition/TableBaseDefinition.php");

class CPatternPerOrch extends TableBaseDefinition {

    public static function getTableName() {
        return "C_PATTERN_PER_ORCH";
    }

    protected static $specificColumns = array(
            "PATTERN_ID"                    => "", 
            "PATTERN_NAME"                  => "", 
            "ITA_EXT_STM_ID"                => "", 
            "TIME_LIMIT"                    => "", 
            "ANS_HOST_DESIGNATE_TYPE_ID"    => "", 
            "ANS_PARALLEL_EXE"              => "", 
            "ANS_WINRM_ID"                  => "", 
            "ANS_GATHER_FACTS"              => "", 
        );

    public static function getRowDiffKeyColumns() {
        throw new BadMethodCallException("Not implemented.");
    }

    public static function getPKColumnName() {
        return "PATTERN_ID";
    }

}

?>
