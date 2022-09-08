INSERT INTO `xp_languages` (`status`, `code`, `code2`, `name`, `locale`, `charset`, `raw_date`, `raw_time`, `raw_datetime`, `format_date`, `format_time`, `format_datetime`, `decimal_point`, `thousands_sep`, `currency_code`, `priority`, `date_updated`, `date_created`) VALUES
(1, 'fi', 'fin', 'Suomi', 'fi_FI.utf8,fi_FI.UTF-8,finnish', 'UTF-8', 'Y-m-d', 'H:i', 'Y-m-d H:i', '%b %e %Y', '%H:%M', '%b %e %Y %H:%M', ',', ' ', '', 0, NOW(), NOW());
-- --------------------------------------------------------
ALTER TABLE `xp_translations` ADD `text_fi` text NOT NULL AFTER `text_en`;
-- --------------------------------------------------------
UPDATE `xp_settings` SET `value` = 'EUR' WHERE `key` in ('site_currency_code', 'default_currency_code');
-- --------------------------------------------------------
UPDATE `xp_currencies` SET `value` = 0.8525 WHERE `code` = 'USD' LIMIT 1;
-- --------------------------------------------------------
UPDATE `xp_currencies` SET `value` = 1 WHERE `code` = 'EUR' LIMIT 1;
-- --------------------------------------------------------
INSERT INTO `xp_geo_zones` (`name`, `description`, `date_updated`, `date_created`) VALUES
('FI VAT Zone', '', NOW(), NOW());
-- --------------------------------------------------------
SET @FI_VAT_ZONE = LAST_INSERT_ID();
-- --------------------------------------------------------
INSERT INTO `xp_zones_to_geo_zones` (`geo_zone_id`, `country_code`, `zone_code`, `date_updated`, `date_created`) VALUES
(@FI_VAT_ZONE, 'FI', '', NOW(), NOW());
-- --------------------------------------------------------
INSERT INTO `xp_geo_zones` (`name`, `description`, `date_updated`, `date_created`) VALUES
('EU VAT Zone', 'European Union excl. FI', NOW(), NOW());
-- --------------------------------------------------------
SET @EU_VAT_ZONE = LAST_INSERT_ID();
-- --------------------------------------------------------
INSERT INTO `xp_zones_to_geo_zones` (`geo_zone_id`, `country_code`, `zone_code`, `date_updated`, `date_created`) VALUES
(@EU_VAT_ZONE, 'AT', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'BE', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'BG', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'CY', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'CZ', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'DE', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'DK', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'EE', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'ES', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'FR', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'GB', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'GR', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'HR', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'HU', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'IE', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'IT', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'LV', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'LT', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'LU', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'MT', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'NL', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'PL', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'PT', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'RO', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'SE', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'SI', '', NOW(), NOW()),
(@EU_VAT_ZONE, 'SK', '', NOW(), NOW());
-- --------------------------------------------------------
INSERT INTO `xp_tax_classes` (`name`, `description`, `date_updated`, `date_created`) VALUES
('Standard', '', NOW(), NOW()),
('Groceries', '', NOW(), NOW()),
('Culture', '', NOW(), NOW());
-- --------------------------------------------------------
INSERT INTO `xp_tax_rates` (`tax_class_id`, `geo_zone_id`, `type`, `name`, `description`, `rate`, `rule_companies_with_tax_id`, `rule_companies_without_tax_id`, `rule_individuals_with_tax_id`, `rule_individuals_without_tax_id`, `date_updated`, `date_created`) VALUES
(1, @FI_VAT_ZONE, 'percent', 'FI VAT 24%', '', 24.0000, 1, 1, 1, 1, NOW(), NOW()),
(1, @EU_VAT_ZONE, 'percent', 'FI VAT 24%', '', 24.0000, 0, 1, 1, 1, NOW(), NOW()),
(2, @FI_VAT_ZONE, 'percent', 'FI VAT 14%', '', 14.0000, 1, 1, 1, 1, NOW(), NOW()),
(2, @EU_VAT_ZONE, 'percent', 'FI VAT 14%', '', 14.0000, 0, 1, 1, 1, NOW(), NOW()),
(3, @FI_VAT_ZONE, 'percent', 'FI VAT 10%', '', 10.0000, 1, 1, 1, 1, NOW(), NOW()),
(3, @EU_VAT_ZONE, 'percent', 'FI VAT 10%', '', 10.0000, 0, 1, 1, 1, NOW(), NOW());
