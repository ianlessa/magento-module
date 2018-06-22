INSERT INTO magento.core_config_data (scope, scope_id, path, value) VALUES
('default', '0', 'dev/template/allow_symlink', '1'),
('default', '0', 'payment/mundipagg_debit/active', '1'),
('default', '0', 'payment/mundipagg_boleto/active', '1'),
('default', '0', 'payment/mundipagg_creditcard/active', '1'),
('default', '0', 'payment/mundipagg_standard/display_total', '1'),
('default', '0', 'payment/mundipagg_recurrencepayment/active', '1'),
('default', '0', 'payment/mundipagg_standard/enable_installments', '1'),
('default', '0', 'payment/mundipagg_standard/cc_types', 'VI,MC,AE,DI,EL,HI'),
('default', '0', 'payment/mundipagg_standard/product_pages_installment_default', NULL),
('default', '0', 'payment/mundipagg_debit/debit_types', '001,237,341,VBV,cielo_mastercard,cielo_visa'),
('default', '0', 'payment/mundipagg_standard/installments', 'a:6:{i:0;a:3:{i:0;s:0:\"\";i:1;s:1:\"1\";i:2;s:0:\"\";}i:1;a:3:{i:0;s:0:\"\";i:1;s:1:\"2\";i:2;s:0:\"\";}i:2;a:3:{i:0;s:0:\"\";i:1;s:1:\"3\";i:2;s:0:\"\";}i:3;a:3:{i:0;s:0:\"\";i:1;s:1:\"4\";i:2;s:1:\"1\";}i:4;a:3:{i:0;s:0:\"\";i:1;s:1:\"5\";i:2;s:1:\"2\";}i:5;a:3:{i:0;s:0:\"\";i:1;s:1:\"6\";i:2;s:1:\"3\";}}')
;

UPDATE magento.core_config_data SET value = '1' where path  = 'payment/mundipagg_twocreditcards/active';

-- magento log
UPDATE magento.core_config_data SET value = '1' WHERE path = 'dev/log/active';
UPDATE magento.core_config_data SET value = 'system.log' WHERE path = 'dev/log/file';
UPDATE magento.core_config_data SET value = 'exception.log' WHERE path = 'dev/log/exception_file';
