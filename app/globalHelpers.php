<?php
use Nwidart\Modules\Facades\Module;
if (!function_exists('isModuleEnabled')) {
    /**
     * Checks if given module is enabled
     *
     * @param $moduleName
     * @return bool
     */
    function isModuleEnabled($moduleName)
    {
        if(Module::has($moduleName)) {
            $status = Module::isEnabled($moduleName);
            if($status) {
                return true;
            }
        }
        return false;
    }


        if (!function_exists('pr')) {
            function pr($var) {
                echo '<pre>';
                print_r($var);
                echo '</pre>';
                exit();
            }
        }
      
    // Parse the query string into an associative array
    function getUrlParams($url)
    {
        // Extract the query string from the URL
        $query = parse_url($url, PHP_URL_QUERY);
        // Parse the query string into an associative array
        parse_str($query, $params);
        return $params;
    }
}
