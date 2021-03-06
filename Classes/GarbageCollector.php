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
 * Description of GarbageCollector
 *
 * @author chris
 */
class GarbageCollector
{

    /**
     * Clean a folder
     * @param string $folder Folder path
     * @param string $age Maximum age for files (e.g. '5 minutes')
     */
    public static function clean($folder, $age)
    {
        $oldestPermitted = strtotime('-'.$age);
        $handle          = opendir($folder);
        while (false !== ($entry           = readdir($handle))) {
            if (($entry !== '.') && ($entry !== '..')) {
                $fileAge = filemtime($folder.$entry);
                if ($fileAge < $oldestPermitted) {
                    \Peregrinus\Cadre\Logger::getLogger()->addDebug('Garbage collector cleaning '.$folder.$entry);
                    unlink($folder.$entry);
                }
            }
        }
    }
}