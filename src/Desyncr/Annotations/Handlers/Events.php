<?php
/**
 * Events handler
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotation\Parser
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Handlers;

/**
 * Class Events
 *
 * @category General
 * @package  Desyncr\Annotations\Handlers
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Events
{
    /**
     * @var
     */
    protected $sm;

    /**
     * @var
     */
    protected $event;

    /**
     * @var string
     */
    protected $config = 'zf-annotations';

    /**
     * @var
     */
    protected $eventHandlers;

    /**
     * Set ups the annotation driver
     *
     * @param \Zend\Mvc\ApplicationInterface                   $application App
     * @param \Desyncr\Annotations\Parser\AnnotationsInterface $annotations Parser
     */
    public function __construct($application, $annotations)
    {
        $this->application = $application;
        $this->sm = $this->application->getServiceManager();

        $config = $this->sm->get('config');
        $config = isset($config[$this->config]) ? $config[$this->config] : array();

        $this->annotations = $annotations;
        $this->setUpEventHandlers($config);
    }

    /**
     * setUpEventHandlers
     *
     * @param Array $config Configuration
     *
     * @return mixed
     * @throws \Exception
     */
    protected function setUpEventHandlers($config)
    {
        if (isset($config['event_handlers'])) {
            $this->eventHandlers = $config['event_handlers'];
        } else {
            throw new \Exception('No event handlers defined!');
        }

    }

    /**
     * setEvent
     *
     * @param \Zend\Mvc\MvcEvent $event MvcEvent instance
     *
     * @return mixed
     */
    public function setEvent($event)
    {
        $this->event = $event;
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
        $controller  = $this->_handleAliases($target->getController());
        $action      = $target->getAction() . 'Action';

        $this->_handleEvent(
            $target->getInstance(),
            $controller,
            $action,
            "Desyncr\\Annotations\\Events\\${event}"
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
        $config = $this->sm->get('config');
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
                $event,
                $this->$annotations
            );
        }
    }

    /**
     * Handles all annotations
     *
     * @param Object $instance       Controller instance
     * @param String $event          Event class name
     * @param Array  $arrAnnotations Annotations array
     *
     * @return mixed
     */
    private function _handleAnnotations(
        $instance,
        $event,
        $arrAnnotations
    ) {
        foreach ($arrAnnotations as $annotation) {
            if (!$this->_handlesEvent($annotation, $event)) {
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
                $this->_executeHook($instance, $annotation);

            }
        }
    }

    /**
     * _handlesEvent
     *
     * @param Object $annotation Annotation
     * @param String $event      Event
     *
     * @return mixed
     */
    private function _handlesEvent($annotation, $event)
    {
        if (!isset($this->eventHandlers[$event])) {
            return false;
        }
        $eventHandler = $this->eventHandlers[$event];
        if (\get_class($annotation) == $eventHandler
            || in_array(
                $eventHandler,
                array_keys(\class_parents($annotation))
            )
        ) {
            return true;
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

        if ($handler->setUp($instance, $this->event, $this->application) !== false) {
            $handler->execute($annotation);
        }
        $handler->tearUp();
    }

    /**
     * Executes a controller annotation block
     *
     * @param Object $instance   Controller instance
     * @param Object $annotation Annotation instance
     *
     * @return mixed
     * @throws \Exception
     */
    private function _executeHook($instance, $annotation)
    {
        $annotation = $this->_parseAnnotationDefinition($annotation->value);
        if ($annotation['class']) {
            $this->_executeStaticHook(
                $annotation['class'],
                $annotation['action'],
                $annotation['params']
            );
        } else {
            $this->_executeInstanceHook(
                $instance,
                $annotation['action'],
                $annotation['params']
            );
        }
    }

    /**
     * _executeStaticHook
     *
     * @param String $class  Class name
     * @param String $method Method name
     * @param mixed  $params Arguments
     *
     * @return mixed
     * @throws \Exception
     */
    private function _executeStaticHook($class, $method, $params)
    {
        if (!\class_exists($class)) {
            throw new \Exception(
                'Class '
                . $class
                . ' doesn\'t exists. Forgot to include it?'
            );
        }
        \call_user_func(
            $method,
            $params,
            $this->event,
            $this->application
        );
    }

    /**
     * _executeInstanceHook
     *
     * @param Object $instance Controller instance
     * @param String $method   Method name
     * @param mixed  $params   Arguments
     *
     * @return mixed
     * @throws \Exception
     */
    private function _executeInstanceHook($instance, $method, $params)
    {
        if (!method_exists($instance, $method)) {
            throw new \Exception(
                'Class '
                . \get_class($instance)
                . ' has no method \'' . $method . '\''
            );
        }
        $instance->$method($params, $this->event, $this->application);
    }

    /**
     * _parseAnnotationDefinition
     *
     * @param Object $annotation Annotation
     *
     * @return mixed
     */
    private function _parseAnnotationDefinition($annotation)
    {
        if (is_array($annotation)) {
            $callback = array_shift($annotation);
        } else {
            $callback = $annotation;
        }
        $action = \lcfirst($callback);
        $action = explode('::', $action);

        if (isset($action[1])) {
            $class = $action[0];
            $method  = $callback;
        } else {
            $method = $action[0];
            $class = null;
        }

        return array(
            'class' => $class,
            'action' => $method,
            'params' => $annotation
        );
    }

}
