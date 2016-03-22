<?php

namespace Peregrinus\Cadre;

/* 
 * Copyright (C) 2016 chris
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

class AbstractObject {

    // use this to override the object's type (e.g. "Provider") in the singular
    // if it can't be automatically inferred from the plural
    protected $objectType = '';
    
    // configuration array
    protected $config = array();
    
    
    public function __construct() {
        $configurationManager = \Peregrinus\Cadre\ConfigurationManager::getInstance();
        $this->config  = $configurationManager->getConfigurationSet($this->getName(), $this->getObjectTypePlural());        
    }
    
    /**
     * Get this object's type in the plural (e.g. "Providers")
     * @return string
     */
    protected function getObjectTypePlural() {
        $tmp = explode('\\', get_class($this));
        return $tmp[count($tmp)-2];        
    }
            
    /**
     * Get this object's type in the singular (e.g. "Provider")
     * @return string
     */
    protected function getObjectType() {
        if ($this->objectType) return $this->objectType;
        $key = $this->getObjectTypePlural();
        if (substr($key, -3) == 'ies') return substr($key, 0, -3).'y';
        return substr($key, 0, -1);
    }
    
    /**
     * Get this object's name
     * @return \string
     */
    protected function getName()
    {
        $class = get_class($this);

        return str_replace($this->getObjectType(), '',
            str_replace(CADRE_appNameSpace.$this->getObjectTypePlural().'\\', '', $class));
    }
    
}