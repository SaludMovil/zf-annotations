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

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;

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
     * @var string
     */
    protected $config = 'zf-annotations';

    /**
     * Registers the Annotation reader
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     */
    public function __construct($e)
    {
        $config = $this->getConfiguration($e);
        $this->ar = $this->setUpAnnotationReader($config);
    }

    /**
     * getConfiguration
     *
     * @param \Zend\Mvc\MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    protected function getConfiguration($e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        return isset($config[$this->config]) ? $config[$this->config] : array();
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
        $autoload = isset($config['autoload']) ? $config['autoload'] : array();
        $this->_registerAutoloadNamespace($autoload);

        $reader = new FileCacheReader(
            new AnnotationReader(),
            $config['cache'],
            $debug = $config['debug']
        );

        return $reader;
    }

    /**
     * _registerAutoloadNamespace
     *
     * @param Array $ns Autoload namespaces array
     *
     * @return mixed
     */
    private function _registerAutoloadNamespace($ns)
    {
        $default = array('Desyncr\\Annotations' => __DIR__ . '/../../../');
        $autoload = array_replace_recursive($default, $ns);

        foreach ($autoload as $ns => $path) {
            AnnotationRegistry::registerAutoloadNamespace($ns, $path);
        }
    }

    /**
     * Reads the annotations for a given class.
     *
     * @param String $class Class name
     *
     * @return mixed
     */
    public function getClassAnnotations($class)
    {
        if (!isset($this->$class)) {
            $this->$class = new \ReflectionClass($class);
        }

        return $this->ar->getClassAnnotations($this->$class);
    }

    /**
     * Reads the annotations for a given class method.
     *
     * @param String $class  Class name
     * @param String $method Method name
     *
     * @return mixed
     */
    public function getMethodAnnotations($class, $method)
    {
        $methodClass = $class . $method;
        if (!isset($this->$methodClass)) {
            $this->$methodClass = new \ReflectionMethod($class, $method);
        }

        return $this->ar->getMethodAnnotations($this->$methodClass);
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
        $annotations = array_merge(
            $this->getClassAnnotations($controllerClass),
            $this->getMethodAnnotations($controllerClass, $method)
        );

        return $annotations;
    }
}
