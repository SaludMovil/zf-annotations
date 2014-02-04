<?php
/**
 * Annotations\AnnotationHandlerInterface
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

/**
 * Class AnnotationHandlerInterface
 *
 * @category General
 * @package  Desyncr\Annotations\Handlers
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
interface AnnotationHandlerInterface
{
    /**
     * General function.
     *
     * @param Object                $instance    Controller instance
     * @param \Zend\Mvc\MvcEvent    $context     Event instance
     * @param \Zend\Mvc\Application $application Annotation object
     *
     * @return mixed
     */
    public function setUp($instance, $context, $application);

    /**
     * Execute handler main logic
     *
     * @param Object $annotation Annotation instance
     *
     * @return mixed
     */
    public function execute($annotation);

    /**
     * Removed any open handler
     *
     * @return mixed
     */
    public function tearUp();
}
 