<?php
/**
 * Module annotation parser.
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Parser
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Parser;

use \Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class Annotations
 *
 * @category General
 * @package  Desyncr\Annotations\Parser
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Annotations
{
    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader|null
     */
    protected $ar = null;

    /**
     * Registers the Annotation reader
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     */
    public function __construct($e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        $this->ar = $this->setUpAnnotationReader($config);
    }

    /**
     * setUpAnnotationReader
     *
     * @param mixed $config Configuration
     *
     * @return mixed
     */
    protected function setUpAnnotationReader($config)
    {
        $autoload = array('Desyncr\\Annotations' => __DIR__ . '/../../../');

        if (isset($config['zf-annotations']['autoload'])) {
            $autoload = array_replace_recursive(
                $autoload,
                $config['zf-annotations']['autoload']
            );
        }

        foreach ($autoload as $ns => $path) {
            AnnotationRegistry::registerAutoloadNamespace($ns, $path);
        }

        return new AnnotationReader();
    }
    /**
     * Reads the annotations for a given class and method (optional).
     *
     * @param String      $controllerClass Controller class to annotate
     * @param String|null $method          Method to get annotations from
     *
     * @return mixed
     */
    public function getAnnotations($controllerClass, $method = null)
    {
        switch ($method) {
        case null:
            if (!isset($this->$controllerClass)) {
                $this->$controllerClass = new \ReflectionClass($controllerClass);
            }

            return $this->ar->getClassAnnotations($this->$controllerClass);

        default:
            $controllerMethodClass = $controllerClass . $method;
            if (!isset($this->$controllerMethodClass)) {
                $this->$controllerMethodClass
                    = new \ReflectionMethod($controllerClass, $method);
            }

            return $this->ar->getMethodAnnotations($this->$controllerMethodClass);
        }
    }

    /**
     * Reads the annotations for a given class and method.
     *
     * @param String      $controllerClass Controller class to annotate
     * @param String|null $method          Method to get annotations from
     *
     * @return mixed
     */
    public function getAllAnnotations($controllerClass, $method)
    {
        // Get class annotations
        $annotations = $this->getAnnotations($controllerClass, null);

        // Get methods annotations
        $annotations = array_merge(
            $annotations,
            $this->getAnnotations($controllerClass, $method)
        );

        return $annotations;
    }
}
