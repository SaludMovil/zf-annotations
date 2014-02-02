<?php
/**
 * Events\EventInterface
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Events;

use Zend\Mvc\MvcEvent;

/**
 * Interface EventInterface
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
interface EventInterface
{
    /**
     * Set ups the event
     *
     * @param MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    public function setUp(MvcEvent $e);

    /**
     * Fires the event handler
     *
     * @param MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    public function onEvent(MvcEvent $e);
}
