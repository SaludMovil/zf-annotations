<?php
namespace Desyncr\ZfAnnotations\Handlers;
use Desyncr\ZfAnnotations\Parser\Annotations;

class Events {
    private $e;

    public function __construct($e) {
        $this->e = $e;
        $this->annotations = new Annotations;
    }

    public function handle($instance, $controller, $action, $event) {
        $controller = $this->handleAliases($controller);
        $action     .= "Action";
        $this->handleEvent($instance, $controller, $action, "Desyncr\\ZfAnnotations\\Annotations\\${event}");
    }

    private function handleAliases($controller) {
        $config = $this->e->getApplication()->getServiceManager()->get('config');
        $invokables = $config['controllers']['invokables'];
        $iterations = 0;
        $max = 10;
        while ($iterations < $max && isset($invokables[$controller])) {
            $controller = $invokables[$controller];
            $iterations++;
        }
        return $controller;
    }

    private function handleEvent($instance, $controller, $action, $event) {
        $annotations = $controller.$action.'annotations';
        if (!isset($this->$annotations)) {
            try {
                $this->$annotations = $this->annotations->getAllAnnotations($controller, $action);
            } catch (\Exception $e) {

            }
        }
        if (isset($this->$annotations)) {
            $this->handleAnnotations($instance, $controller, $event, $this->$annotations);
        }
    }

    private function handleAnnotations($instance, $controller, $event, $arrAnnotations) {
        foreach ($arrAnnotations as $annotation) {
            if (\get_class($annotation) == $event) {
                $this->execAnnotationHook($instance, $controller, $annotation);
            }
        }
    }

    private function execAnnotationHook($instance, $controller, $annotation) {
        $action = \lcfirst($annotation->value);
        $action = explode('::', $action);
        if (isset($action[1])) {
            if (!class_exists($action[0])) {
                throw new \Exception("Class '$action[0]' doesn't exists. Forgot to include it?");
                return;
            }
            call_user_func($action, $this->e->getApplication());
        } else {
            $instance->$action[0]($this->e->getApplication());
        }
    }
}
