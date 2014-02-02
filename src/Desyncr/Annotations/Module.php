<?php
/**
 * Desyncr\Annotations main module
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @category General
 * @package  Desyncr\Annotations
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  http://gpl.gnu.org GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * On bootstrap event.
     *
     * @param MvcEvent $e MvcEvent instance
     *
     * @return mixed
     */
    public function onBootstrap(MvcEvent $e)
    {
        $app    = $e->getApplication();
        $em     = $app->getEventManager();
        $sm     = $em->getSharedManager();

        $sm->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            MvcEvent::EVENT_DISPATCH,
            array(new \Desyncr\Annotations\Events\Init, 'onEvent'),
            100
        );
        $em->attach(
            MvcEvent::EVENT_DISPATCH,
            array(new \Desyncr\Annotations\Events\Dispatch, 'onEvent')
        );
        $em->attach(
            MvcEvent::EVENT_RENDER,
            array(new \Desyncr\Annotations\Events\Render, 'onEvent')
        );
        $em->attach(
            MvcEvent::EVENT_ROUTE,
            array(new \Desyncr\Annotations\Events\Route, 'onEvent')
        );
    }

    /**
     * Returns autoloader configuration.
     *
     * @return mixed
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespace' => array(__NAMESPACE__ => __DIR__)
            )
        );
    }

    /**
     * Returns Module configuration.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    /**
     * Returns service configuration.
     *
     * @return mixed
     */
    public function getServiceConfig()
    {
        return array();
    }

}
