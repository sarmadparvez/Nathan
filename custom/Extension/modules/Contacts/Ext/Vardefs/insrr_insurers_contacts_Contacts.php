<?php
// created: 2015-04-06 17:34:37
$dictionary["Contact"]["fields"]["insrr_insurers_contacts"] = array (
  'name' => 'insrr_insurers_contacts',
  'type' => 'link',
  'relationship' => 'insrr_insurers_contacts',
  'source' => 'non-db',
  'module' => 'insrr_Insurers',
  'bean_name' => false,
  'vname' => 'LBL_INSRR_INSURERS_CONTACTS_FROM_INSRR_INSURERS_TITLE',
  'id_name' => 'insrr_insurers_contactsinsrr_insurers_ida',
);
$dictionary["Contact"]["fields"]["insrr_insurers_contacts_name"] = array (
  'name' => 'insrr_insurers_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_INSRR_INSURERS_CONTACTS_FROM_INSRR_INSURERS_TITLE',
  'save' => true,
  'id_name' => 'insrr_insurers_contactsinsrr_insurers_ida',
  'link' => 'insrr_insurers_contacts',
  'table' => 'insrr_insurers',
  'module' => 'insrr_Insurers',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["insrr_insurers_contactsinsrr_insurers_ida"] = array (
  'name' => 'insrr_insurers_contactsinsrr_insurers_ida',
  'type' => 'link',
  'relationship' => 'insrr_insurers_contacts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_INSRR_INSURERS_CONTACTS_FROM_CONTACTS_TITLE',
);
