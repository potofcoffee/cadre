<?php
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

namespace Peregrinus\Cadre;

/**
 * Abstract factory class
 *
 * @author Christoph Fischer <chris@toph.de>
 */
class AbstractFactory
{
    static protected $ns = '';
    static protected $pluralName = '';
    
    static public function getPluralName() {
        if (self::$pluralName) return self::$pluralName;
        $key = self::getKey();
        if (substr($key, -1) == 'y') return substr($key, 0, -1).'ies';
        return $key.'s';
    }

    static public function getAllClasses($type = NULL)
    {
        // infer type from own class name
        if (!$type) $type = self::getKey();
        $type          = ucfirst($type);
        $typeAbstract  = 'Abstract'.$type;
        $typeMulti     = self::getPluralName();
        $typeNamespace = CADRE_appNameSpace.$typeMulti;
        $typePath      = CADRE_basePath.'Classes/'.$typeMulti.'/';

        $classes = array();
        // find all matching classes
        $handle  = opendir($typePath);
        while ($file    = readdir($handle)) {
            if (substr($file, -(strlen($type) + 4)) == $type.'.php') {
                if ($file != $typeAbstract.'.php') {
                    $thisType           = substr($file, 0, -(strlen($type) + 4));
                    $thisClass          = $typeNamespace.'\\'.$thisType.$type;
                    $classes[$thisType] = $thisClass;
                }
            }
        }
        closedir($handle);
        return $classes;
    }

    /**
     * Get this factory's key (class without namespace and 'Factory')
     * @return \string
     */
    static public function getKey()
    {
        $class = get_called_class();
        return str_replace('Factory', '',
            str_replace(CADRE_appNameSpace.'Factories\\', '', $class));
    }

    /**
     * Get a single object
     * @param \string $key Key
     * @return object Template Object
     */
    static public function get($key)
    {
        $factoryType = self::getKey();
        $ns          = (self::$ns ? self::$ns : $factoryType.'s');
        $className   = '\\VMFDS\\CADRE\\'.$ns.'\\'.ucFirst($key).$factoryType;
        if (class_exists($className)) {
            return new $className();
        } else {
            return false;
        }
    }
}