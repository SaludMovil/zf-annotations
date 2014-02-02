<?php
/**
 * Events\AbstractEvent
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Events;

use Zend\Mvc\MvcEvent;
use Desyncr\Annotations\Handlers\Target;

/**
 * Class AbstractEvent
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * @var
     */
    protected $target;

    /**
     * @var
     */
    protected $handler;

    /**
     * @var
     */
    protected $event;

    /**
     * @var
     */
    protected $annotations;

    /**
     * Instance a new Events handles and annotation reader
     *
     * @param Object $handler Annotation handler
     *
     * @return mixed
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Sets up the event
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    public function setUp(MvcEvent $e)
    {
        $this->target = new Target($e);
        $this->handler->setEvent($e);
    }

    /**
     * OnEvent handler
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    public function onEvent(MvcEvent $e)
    {
        $this->setUp($e);
        $handler = $this->handler;
        $target = $this->target;
        if (!$target->getController()) {
            return;
        }
        $handler->handle($target, $this->event);
    }

    /**
     * Sets the current event
     *
     * @param mixed $event Event
     *
     * @return mixed
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * Gets the current event
     *
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the handler
     *
     * @param mixed $handler Handler
     *
     * @return mixed
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Gets the handler
     *
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Sets the target
     *
     * @param mixed $target Target
     *
     * @return mixed
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Gets the target
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }
}
