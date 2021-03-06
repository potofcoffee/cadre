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
 * Description of Logger
 *
 * @author chris
 */
class Logger
{
    static protected $instance = null;

    /** @var \Monolog\Logger|null  */
    protected $logger          = null;

    /**
     * Get an instance of the logger object
     * @return \Peregrinus\Cadre\Logger Instance of session object
     */
    static public function getInstance(): Logger
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get an instance of the attached Monolog Logger
     * @return \Monolog\Logger
     */
    static public function getLogger(): \Monolog\Logger
    {
        $me = self::getInstance();
        return $me->logger;
    }

    static public function initialize()
    {
        // call getInstance to force construction of new instance
        $me = self::getInstance();
    }

    protected function __construct()
    {
        $this->logger = new \Monolog\Logger(CADRE_appKey);
        if (CADRE_debug) {
            $this->logger->pushHandler(new \Monolog\Handler\StreamHandler(
                CADRE_basePath.'Logs/'.CADRE_appKey.'.debug.log', \Monolog\Logger::DEBUG));
        }
        $this->logger->pushHandler(new \Monolog\Handler\StreamHandler(
            CADRE_basePath.'Logs/'.CADRE_appKey.'.notice.log', \Monolog\Logger::NOTICE));
    }

    final private function __clone()
    {

    }
}