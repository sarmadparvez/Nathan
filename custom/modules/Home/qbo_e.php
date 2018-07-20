<?php

echo 'QBO E';

$administration = BeanFactory::getBean('Administration');

$value = 'test string to be encrypted';

echo '<br/>'.$encrypted_value = $administration->encrpyt_before_save($value);

echo '<br/>'.$decrypted_value = $administration->decrypt_after_retrieve($encrypted_value);