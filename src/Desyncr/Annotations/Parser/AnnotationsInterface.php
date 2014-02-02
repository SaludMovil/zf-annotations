<?php
/**
 * Parser\AnnotationsInterface
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Parser\AnnotationsInterface
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Parser;

/**
 * Interface AnnotationsInterface
 *
 * @category General
 * @package  Desyncr\Annotations\Parser
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
interface AnnotationsInterface
{
    /**
     * Reads the annotations for a given class.
     *
     * @param String $class Class name
     *
     * @return mixed
     */
    public function getClassAnnotations($class);

    /**
     * Reads the annotations for a given class method.
     *
     * @param String $class  Class name
     * @param String $method Method name
     *
     * @return mixed
     */
    public function getMethodAnnotations($class, $method);

    /**
     * Reads the annotations for a given class and method.
     *
     * @param String      $controllerClass Controller class to annotate
     * @param String|null $method          Method to get annotations from
     *
     * @return mixed
     */
    public function getAllAnnotations($controllerClass, $method);
}
 