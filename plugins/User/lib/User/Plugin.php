<?php 
class User_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface{
	

	public static function install(){
		return "User Plugin successfully installed.";		
	}
	
	public static function uninstall(){
		return "User Plugin successfully uninstalled.";
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
        if(file_exists(PIMCORE_PLUGINS_PATH . "/User/texts/" . $language . ".csv")){
            return "/User/texts/" . $language . ".csv";
        }
        return "/User/texts/en.csv";
    }

}