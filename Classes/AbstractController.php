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

class AbstractController {

    const REDIRECT_HEADER = 0x01;
    const REDIRECT_JAVASCRIPT = 0x02;

    private $conf = array();
    private $configurationManager = NULL;
    protected $defaultAction = '';
    protected $viewPath = 'Views/';
    protected $viewLoader = NULL;
    protected $view = NULL;
    protected $showView = TRUE;
    protected $request = NULL;
    protected $encodingFunction = NULL;

    public function __construct() {
        $confManager = $this->getConfigurationManager();
        $this->conf = $confManager->getConfigurationSet('CADRE');
        $this->request = \Peregrinus\Cadre\Request::getInstance();
    }

    protected function initializeView() {
        // get the view
        $this->view = new \Peregrinus\Cadre\View($requestedAction);
        $this->view->setViewPath(CADRE_viewPath . $this->getName() . '/');
    }

    protected function initializeController() {
        
    }

    /**
     * Process action routing
     *
     * @return void
     * @throws \Exception
     */
    public function dispatch() {
        $request = \Peregrinus\Cadre\Request::getInstance();

        if (!$request->hasArgument('action')) {
            // redirect to default action
            $defaultAction = $this->defaultAction ? $this->defaultAction : 'default';
            $this->redirectToAction($defaultAction);
        }
        $requestedAction = $request->getArgument('action');
        $actionMethod = $requestedAction . 'Action';
        if (!method_exists($this, $actionMethod)) {
            \Peregrinus\Cadre\Logger::getLogger()->addEmergency(
                    'Method "' . $actionMethod . '" not implemented in controller' . get_class($this) . ' .');
            throw new \Exception('Method "' . $requestedAction . '" not implemented in this controller.', 0x01);
        } else {
            // run the initialize and action methods
            $this->initializeView();
            $this->initializeController();
            $result = $this->$actionMethod();
            if ($result !== FALSE) {
                // render the view
                if ($this->showView) {
                    $this->view->sendContentTypeHeader();
                    $this->renderView($this->showView, $result);
                }
            }
        }
    }

    /**
     * Get an instance of the configuration manager
     * @return \Peregrinus\Cadre\ConfigurationManager Configuration manager object
     */
    protected function getConfigurationManager() {
        if (is_null($this->configurationManager)) {
            $this->configurationManager = \Peregrinus\Cadre\ConfigurationManager::getInstance();
        }
        return $this->configurationManager;
    }

    /**
     * Get this controllers's name (class without namespace and 'Provider')
     * @return \string
     */
    public function getName() {
        $class = get_class($this);
        return str_replace('Controller', '', str_replace(CADRE_appNameSpace . 'Controllers\\', '', $class));
    }

    /**
     * Redirect to another action
     * @param \string $action
     * @param \int $redirectMethod Method of redirecting
     * @param \int $delay Delay in ms (only with javascript redirect)
     */
    protected function redirectToAction($action, $redirectMethod = self::REDIRECT_HEADER, $delay = 0) {
        \Peregrinus\Cadre\Router::getInstance()->redirect(
                strtolower($this->getName()), $action, null, null, $redirectMethod, $delay);
    }

    /**
     * Get default action name for this controller
     * @return \string Default action name
     */
    function getDefaultAction() {
        return $this->defaultAction;
    }

    /**
     * Set default action name for this controller
     * @param \string $defaultAction Default action name
     * @return void
     */
    function setDefaultAction($defaultAction) {
        $this->defaultAction = $defaultAction;
    }

    /**
     * Switch off view handling
     * @return void
     */
    public function dontShowView() {
        $this->showView = false;
    }

    /**
     * Set the encoding function
     * @param string $encodingFunction Method or function name
     */
    public function setEncodingFunction($encodingFunction) {
        $this->encodingFunction = $encodingFunction;
    }

    /**
     * Render the view now
     * @param bool $show Output the view right away
     * @param string $overrideContents Override contents
     */
    public function renderView($show = true, $overrideContents) {
        $rendered = $overrideContents ? $overrideContents : $this->view->render();
        // final encoding function?
        if ($func = $this->encodingFunction) {
            if (method_exists($this, $func)) {
                $rendered = $this->$func($rendered);
            } elseif (function_exists($func)) {
                $rendered = $func($rendered);
            }
        }
        if ($show) {
            echo $rendered;
        }
        // prevent showing twice:
        $this->dontShowView();
        return $rendered;
    }

}
