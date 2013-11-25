<?php
namespace Desyncr\ZfAnnotations\Events;
use Zend\Mvc\MvcEvent as MvcEvent;
use Desyncr\ZfAnnotations\Handlers\Events;
use Desyncr\ZfAnnotations\Handlers\Target;
use Desyncr\ZfAnnotations\Events\EventInterface;

abstract class AbstractEvent implements EventInterface {
    protected $target;
    protected $handler;
    protected $event;

    public function setUp(MvcEvent $e) {
        $this->target = new Target($e);
        $this->handler = new Events($e);
    }

    public function onEvent(MvcEvent $e) {
        $this->setUp($e);
        $handler = $this->handler;
        $target = $this->target;
        if ($target->getController()) {
            $handler->handle($target->getInstance(), $target->getController(), $target->getAction(), $this->event);
        }
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event) {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param mixed $handler
     */
    public function setHandler($handler) {
        $this->handler = $handler;
    }

    /**
     * @return mixed
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target) {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTarget() {
        return $this->target;
    }
}
