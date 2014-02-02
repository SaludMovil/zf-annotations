<?php
/**
 * Events handler
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotation\Parser
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Handlers;

use Desyncr\Annotations\Parser\Annotations;

/**
 * Class Events
 *
 * @category General
 * @package  Desyncr\Annotations\Handlers
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Events
{
    /**
     * @var
     */
    private $_e;

    /**
     * Set ups the annotation driver
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     */
    public function __construct($e)
    {
        $this->_e = $e;
        $this->annotations = new Annotations;
    }

    /**
     * Handles a given MvcEvent
     *
     * @param Object $target Controller instance
     * @param Object $event  Event instance
     *
     * @return mixed
     */
    public function handle($target, $event)
    {
        $controller = $this->_handleAliases($target->getController());
        $action     = $target->getAction() . 'Action';
        $this->_handleEvent(
            $target->getInstance(),
            $controller,
            $action,
            "Desyncr\\Annotations\\Annotations\\${event}"
        );
    }

    /**
     * Determine if a controller is using an alias.
     *
     * @param String $controller Controller class name
     *
     * @return mixed
     */
    private function _handleAliases($controller)
    {
        $config = $this->_e->getApplication()->getServiceManager()->get('config');
        $invokables = $config['controllers']['invokables'];
        $iterations = 0;
        $max = 10;
        while ($iterations < $max && isset($invokables[$controller])) {
            $controller = $invokables[$controller];
            $iterations++;
        }
        return $controller;
    }

    /**
     * Handles a given MvcEvent
     *
     * @param Object $instance   Controller instance
     * @param String $controller Controller class name
     * @param String $action     Controller action name
     * @param String $event      Event class name
     *
     * @return mixed
     */
    private function _handleEvent($instance, $controller, $action, $event)
    {
        $annotations = $controller.$action.'annotations';
        if (!isset($this->$annotations)) {
            $this->$annotations = $this->annotations->getAllAnnotations(
                $controller, $action
            );
        }
        if (isset($this->$annotations)) {
            $this->_handleAnnotations(
                $instance,
                $controller,
                $event,
                $this->$annotations
            );
        }
    }

    /**
     * Handles all annotations
     *
     * @param Object $instance       Controller instance
     * @param String $controller     Controller class name
     * @param String $event          Event class name
     * @param Array  $arrAnnotations Annotations array
     *
     * @return mixed
     */
    private function _handleAnnotations(
        $instance,
        $controller,
        $event,
        $arrAnnotations
    ) {
        foreach ($arrAnnotations as $annotation) {

            if (\get_class($annotation) != $event
                && !in_array($event, array_keys(\class_parents($annotation)))
            ) {
                continue;
            }

            if (isset($annotation->handler)) {
                $handler = $annotation->handler;
                $this->_executeHandler($instance, $handler, $annotation);

            } else if (\method_exists($annotation, 'setUp')) {
                $this->_executeHandler(
                    $instance,
                    $annotation, /* the handler is the annotation itself */
                    $annotation
                );

            } else {
                $this->_executeHook($instance, $controller, $annotation);

            }
        }
    }

    /**
     * Executes an inline or class annotation handler
     *
     * @param Object $instance   Controller instance
     * @param String $handler    Handler class name
     * @param Object $annotation Annotation instance
     *
     * @return mixed
     * @throws \Exception
     */
    private function _executeHandler($instance, $handler, $annotation)
    {
        $handler = new $handler;
        if (!in_array(
            'Desyncr\Annotations\Handlers\AnnotationHandlerInterface',
            array_keys(class_implements($handler))
        )) {
            throw new \Exception(
                'Annotation handler must implement AnnotationHandlerInterface'
            );
        }

        if ($handler->setUp($instance, $this->_e, $annotation) !== false) {
            $handler->execute($instance);
        }
        $handler->tearUp();
    }

    /**
     * Executes a controller annotation block
     *
     * @param Object $instance   Controller instance
     * @param String $controller Controller class name
     * @param Object $annotation Annotation instance
     *
     * @return mixed
     * @throws \Exception
     */
    private function _executeHook($instance, $controller, $annotation)
    {
        $action = \lcfirst($annotation->value);
        list($class, $method) = explode('::', $action);

        if (isset($method)) {
            if (!\class_exists($class)) {
                throw new \Exception(
                    "Class '$class' doesn't exists. Forgot to include it?"
                );
            }
            \call_user_func($action, $this->_e->getApplication());
        } else {
            $instance->$class($this->_e->getApplication());
        }
    }
}
