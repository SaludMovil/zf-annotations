<?php
namespace Desyncr\ZfAnnotations\Events;

use Zend\Mvc\MvcEvent;

interface EventInterface {
    public function setUp(MvcEvent $e);
    public function onEvent(MvcEvent $e);
}
