<?php
namespace Desyncr\ZfAnnotations\Handlers;
use Zend\Mvc\MvcEvent as MvcEvent;

class Target {
    private $matches;
    private $controller;
    private $action;
    private $instance;

    public function __construct(MvcEvent $e) {
        $this->instance     = $e->getTarget();
        $this->matches      = $e->getRouteMatch();
        if ($this->matches) {
            $this->controller   = $this->matches->getParam('controller');
            $this->action       = $this->matches->getParam('action');
        }
    }

    /**
     * @param mixed $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * @param mixed $instance
     */
    public function setInstance($instance) {
        $this->instance = $instance;
    }

    /**
     * @param mixed $matches
     */
    public function setMatches($matches) {
        $this->matches = $matches;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getInstance() {
        return $this->instance;
    }

    /**
     * @return mixed
     */
    public function getMatches() {
        return $this->matches;
    }

}
