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

class AbstractJSONController extends \Peregrinus\Cadre\AbstractController {
    
    /**
     * Initialize the view object
     * @param string $requestedAction Requested action
     */
    protected function initializeView($requestedAction) {
        // just setup a raw view object
        $this->view = new \Peregrinus\Cadre\View($requestedAction);
    }

    protected function initializeController() {
        $this->view->setContentType('application/json');        
        $this->setEncodingFunction('json_encode');
    }
    
    
}