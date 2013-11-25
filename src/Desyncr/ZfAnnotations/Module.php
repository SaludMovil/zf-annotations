<?php
namespace Desyncr\ZfAnnotations;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface {
    public function onBootstrap(MvcEvent $e) {
        $app      = $e->getApplication();
        $this->em = $em = $app->getEventManager();
        $sm       = $this->em->getSharedManager();

        $sm->attach('Zend\Mvc\Controller\AbstractActionController',
            MvcEvent::EVENT_DISPATCH, array(new \Desyncr\ZfAnnotations\Events\Init, 'onEvent'), 100);

        $em->attach(MvcEvent::EVENT_DISPATCH, array(new \Desyncr\ZfAnnotations\Events\Dispatch, 'onEvent'));
        $em->attach(MvcEvent::EVENT_RENDER, array(new \Desyncr\ZfAnnotations\Events\Render, 'onEvent'));
        $em->attach(MvcEvent::EVENT_ROUTE, array(new \Desyncr\ZfAnnotations\Events\Render, 'onEvent'));
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespace' => array(__NAMESPACE__ => __DIR__)
            )
        );
    }

    public function getConfig() {
        return array();
    }

    public function getServiceConfig() {
        return array();
    }

}
