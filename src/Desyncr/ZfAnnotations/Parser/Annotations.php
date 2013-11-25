<?php
namespace Desyncr\ZfAnnotations\Parser;
use \Doctrine\Common\Annotations\AnnotationReader as AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Annotations {
    protected $ar = null;

    public function __construct() {
        AnnotationRegistry::registerAutoloadNamespace("Desyncr\\ZfAnnotations", __DIR__ . '/../../../');
        $this->ar = new AnnotationReader();
    }

    public function getAnnotations($controllerClass, $method = null) {
        switch ($method) {
            case null:
                if (!isset($this->$controllerClass)) {
                    $this->$controllerClass = new \ReflectionClass($controllerClass);
                }

                return $this->ar->getClassAnnotations($this->$controllerClass);

            default:
                $controllerMethodClass = $controllerClass . $method;
                if (!isset($this->$controllerMethodClass)) {
                    $this->$controllerMethodClass = new \ReflectionMethod($controllerClass, $method);
                }

                return $this->ar->getMethodAnnotations($this->$controllerMethodClass);
        }
    }

    public function getAllAnnotations($controllerClass, $method) {
        // Get class annotations
        $annotations = $this->getAnnotations($controllerClass, null);

        // Get methods annotations
        $annotations = array_merge($annotations, $this->getAnnotations($controllerClass, $method));

        return $annotations;
    }
}
