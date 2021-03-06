-- *****************************************************************************
-- *** ***** CreateParameterMenu Tables                                      ***
-- *****************************************************************************
CREATE TABLE F_HOSTGROUP_VAR
(
ROW_ID                             %INT%                           , -- 識別シーケンス項番

HOSTGROUP_NAME                     %INT%                           ,
VARS_NAME                          %VARCHR% (128)                  ,
HOSTNAME                           %INT%                           ,

DISP_SEQ                           %INT%                           , -- 表示順序
NOTE                               %VARCHR% (4000)                 , -- 備考
DISUSE_FLAG                        %VARCHR% (1)                    , -- 廃止フラグ
LAST_UPDATE_TIMESTAMP              %DATETIME6%                     , -- 最終更新日時
LAST_UPDATE_USER                   %INT%                           , -- 最終更新ユーザ

PRIMARY KEY (ROW_ID)
)%%TABLE_CREATE_OUT_TAIL%%;

CREATE TABLE F_HOSTGROUP_VAR_JNL
(
JOURNAL_SEQ_NO                     %INT%                           , -- 履歴用シーケンス
JOURNAL_REG_DATETIME               %DATETIME6%                     , -- 履歴用変更日時
JOURNAL_ACTION_CLASS               %VARCHR% (8)                    , -- 履歴用変更種別

ROW_ID                             %INT%                           , -- 識別シーケンス項番

HOSTGROUP_NAME                     %INT%                           ,
VARS_NAME                          %VARCHR% (128)                  ,
HOSTNAME                           %INT%                           ,

DISP_SEQ                           %INT%                           , -- 表示順序
NOTE                               %VARCHR% (4000)                 , -- 備考
DISUSE_FLAG                        %VARCHR% (1)                    , -- 廃止フラグ
LAST_UPDATE_TIMESTAMP              %DATETIME6%                     , -- 最終更新日時
LAST_UPDATE_USER                   %INT%                           , -- 最終更新ユーザ
PRIMARY KEY(JOURNAL_SEQ_NO)
)%%TABLE_CREATE_OUT_TAIL%%;

