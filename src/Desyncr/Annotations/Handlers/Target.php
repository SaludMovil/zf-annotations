<?php
/**
 * Target class
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Handlers
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Handlers;

use Zend\Mvc\MvcEvent as MvcEvent;

/**
 * Class Target
 *
 * @category General
 * @package  Desyncr\Annotations\Handlers
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Target
{
    /**
     * @var \Zend\Mvc\Router\RouteMatch
     */
    protected $matches;

    /**
     * @var mixed
     */
    protected $controller;

    /**
     * @var mixed
     */
    protected $action;

    /**
     * @var object|string
     */
    protected $instance;

    /**
     * Sets up initials instances
     *
     * @param MvcEvent $e MvcEvent instance
     */
    public function __construct(MvcEvent $e)
    {
        $this->instance     = $e->getTarget();
        $this->matches      = $e->getRouteMatch();
        if ($this->matches) {
            $this->controller   = $this->matches->getParam('controller');
            $namespace = $this->matches->getParam('__NAMESPACE__');
            if (!stristr($this->controller, $namespace)) {
                $this->controller = $namespace . "\\" . $this->controller;
            }
            $this->action       = $this->matches->getParam('action');
        }
    }

    /**
     * Sets up the action
     *
     * @param mixed $action Controller action name
     *
     * @return null
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Sets up the controller
     *
     * @param mixed $controller Controller name
     *
     * @return null
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Sets up the controller instance
     *
     * @param mixed $instance Controller instance
     *
     * @return null
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Sets up the matches instance
     *
     * @param mixed $matches Matches instance
     *
     * @return null
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * Returns the action name
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns the controller name
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Returns the controller instance
     *
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Returns the matches instance
     *
     * @return mixed
     */
    public function getMatches()
    {
        return $this->matches;
    }

}
