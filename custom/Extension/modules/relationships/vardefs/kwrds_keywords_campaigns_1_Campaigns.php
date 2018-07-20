<?php
// created: 2015-04-08 11:45:30
$dictionary["Campaign"]["fields"]["kwrds_keywords_campaigns_1"] = array (
  'name' => 'kwrds_keywords_campaigns_1',
  'type' => 'link',
  'relationship' => 'kwrds_keywords_campaigns_1',
  'source' => 'non-db',
  'module' => 'KWRDS_Keywords',
  'bean_name' => 'KWRDS_Keywords',
  'vname' => 'LBL_KWRDS_KEYWORDS_CAMPAIGNS_1_FROM_KWRDS_KEYWORDS_TITLE',
  'id_name' => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
);
$dictionary["Campaign"]["fields"]["kwrds_keywords_campaigns_1_name"] = array (
  'name' => 'kwrds_keywords_campaigns_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_KWRDS_KEYWORDS_CAMPAIGNS_1_FROM_KWRDS_KEYWORDS_TITLE',
  'save' => true,
  'id_name' => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
  'link' => 'kwrds_keywords_campaigns_1',
  'table' => 'kwrds_keywords',
  'module' => 'KWRDS_Keywords',
  'rname' => 'name',
);
$dictionary["Campaign"]["fields"]["kwrds_keywords_campaigns_1kwrds_keywords_ida"] = array (
  'name' => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
  'type' => 'link',
  'relationship' => 'kwrds_keywords_campaigns_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_KWRDS_KEYWORDS_CAMPAIGNS_1_FROM_CAMPAIGNS_TITLE',
);
