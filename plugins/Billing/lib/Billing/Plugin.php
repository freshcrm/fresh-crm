<?php 
class Billing_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface{
	

	public static function install(){
        Pimcore_API_Plugin_Abstract::getDb()->exec("CREATE TABLE IF NOT EXISTS `orders` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `payment_id` varchar(128) COLLATE utf8_bin DEFAULT NULL,
					  `user_id` int(10) unsigned DEFAULT NULL,
					  `user_fb_id` bigint(20) unsigned DEFAULT NULL,
					  `discount` double NOT NULL,
					  `price_total` double NOT NULL,
					  `tax` double NOT NULL,
					  `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					  `paid` timestamp NULL DEFAULT NULL,
					  `payment_method` enum('UPN','CARD','MONETA','KLIK','GIFT','PAYPAL') CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `result` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `auth` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `ref` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `tranID` varchar(255) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `postDate` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `trackID` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `response_code` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
					  `err_msg` varchar(10) CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
					  `err_text` varchar(150) CHARACTER SET utf8 COLLATE utf8_slovenian_ci NOT NULL,
					  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `refresh_counter` tinyint(3) unsigned DEFAULT '0',
					  `referrer` varchar(32) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `sid` char(32) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
					  `respBody` text CHARACTER SET utf8 COLLATE utf8_slovenian_ci,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

        /*
         * CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `paymentId` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `entity` tinyint(1) DEFAULT NULL,
  `userId` int(10) unsigned DEFAULT NULL,
  `discount` double DEFAULT '0',
  `priceTotal` double NOT NULL,
  `tax` double DEFAULT NULL,
  `startDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `paid` bigint(20) DEFAULT NULL,
  `type` enum('DELIVERY','PICKUP','BOX') COLLATE utf8_bin DEFAULT NULL,
  `paymentMethod` enum('UPN','CARD','MONETA','KLIK','GIFT','PAYPAL','ACCOUNT_CREDIT','CASH') CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `result` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `auth` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `ref` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `tranId` varchar(255) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `trackId` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `responseCode` varchar(45) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `errMsg` varchar(10) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `referrer` varchar(32) CHARACTER SET utf8 COLLATE utf8_slovenian_ci DEFAULT NULL,
  `respBody` text CHARACTER SET utf8 COLLATE utf8_slovenian_ci,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=285 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount` int(11) DEFAULT '0',
  `quantity` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=940 ;
         */

        Pimcore_API_Plugin_Abstract::getDb()->exec("ALTER TABLE `orders` ADD INDEX (user_id), ADD INDEX (ref)");

        if (self::isInstalled()) {
            $statusMessage = "Billing Plugin successfully installed.";
        } else {
            $statusMessage = "Billing Plugin could not be installed";
        }
        return $statusMessage;
	}
	
	public static function uninstall(){
        //Pimcore_API_Plugin_Abstract::getDb()->exec("DROP TABLE `orders`");
        // @TODO: copy table or export it

        if (!self::isInstalled()) {
            $statusMessage = "Billing Plugin successfully uninstalled.";
        } else {
            $statusMessage = "Billing Plugin could not be uninstalled";
        }
        return $statusMessage;		
	}

	public static function isInstalled(){
		return true;
	}

    /**
     *
     * @param string $language
     * @return string path to the translation file relative to plugin direcory
     */
    public static function getTranslationFile($language) {
        if(file_exists(PIMCORE_PLUGINS_PATH . "/Billing/texts/" . $language . ".csv")){
            return "/Billing/texts/" . $language . ".csv";
        }
        
        return "/Billing/texts/en.csv";
    }

}