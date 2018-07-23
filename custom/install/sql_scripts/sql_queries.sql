# The Following queries should be executed before installing the package LeadRuotingIssueFix.zip

REPLACE INTO `fields_meta_data` (`id`, `name`, `vname`, `comments`, `help`, `custom_module`, `type`, `len`, `required`, `default_value`, `date_modified`, `deleted`, `audited`, `massupdate`, `duplicate_merge`, `reportable`, `importable`, `ext1`, `ext2`, `ext3`, `ext4`) 
VALUES ('Leadsfourth_assignment_time_c', 'fourth_assignment_time_c', 'LBL_FOURTH_ASSIGNMENT_TIME_C', '', '', 'Leads', 'datetimecombo', NULL, 0, '', '2018-07-20 11:09:45', 0, 0, 0, 0, 1, 'true', '', '', '', '');

UPDATE leads_cstm set first_assignment_time_c = NOW() where first_assignment_time_c IS NULL;
UPDATE leads_cstm set second_assignment_time_c = NOW() where second_assignment_time_c IS NULL;
UPDATE leads_cstm set third_assignment_time_c = NOW() where third_assignment_time_c IS NULL;


# The following queries should be executed after installtion of package LeadRuotingIssueFix.zip and Running QRR

UPDATE leads_cstm set fourth_assignment_time_c = NOW() where fourth_assignment_time_c IS NULL;
