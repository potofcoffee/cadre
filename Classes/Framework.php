<?php

namespace Peregrinus\Cadre;

/*
 * CADRE
 * Lightweight PHP application framework
 * http://github.com/potofcoffee/cadre
 *
 * Copyright (c) Christoph Fischer, http://christoph-fischer.org
 * Author: Christoph Fischer, chris@toph.de
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Framework {
    
    static public function setConstants($appName = '', $options) {                
        define('CADRE_version', '0.0.2');
        define('CADRE_software', 'CADRE ' . CADRE_version);
        
        define ('CADRE_app', $appName);
        $appName = strtoupper($appName);
        define ('CADRE_appKey', $appName.'_');
        
        if (isset($options['version'])) define(CADRE_appKey.'version', $options['version']);

        // CADRE_debug can be set to true before setConstants() is called
        if (!defined('CADRE_debug')) define('CADRE_debug', false);
        define (CADRE_appKey.'debug', CADRE_debug);
        
        if (isset($options['basePath'])) {
            define(CADRE_appKey.'basePath', $options['basePath'].'/');
            define('CADRE_basePath', $options['basePath'].'/');
        } else {
            define('CADRE_basePath', __DIR__);
        }
        if (isset($options['namespace'])) {
            define('CADRE_appNameSpace', $options['namespace']);
        } else {
            define('CADRE_appNameSpace', 'Peregrinus\\Cadre\\');
        }
        
        define('CADRE_viewPath', CADRE_basePath . 'Resources/Private/Views/');
        define(CADRE_appKey.'viewPath', CADRE_viewPath);

        define('CADRE_baseUrl', (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] 
        . dirname(parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH)) . '/');
        define (CADRE_appKey.'baseUrl', CADRE_baseUrl);

        // error handling stuff:
        if (CADRE_debug) {
            ini_set('display_errors', 1);
            ini_set('error_log', CADRE_basePath . 'Logs/php-errors');
        }        
    }
    
}
