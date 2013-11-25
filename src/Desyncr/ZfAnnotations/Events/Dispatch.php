<?php
namespace Desyncr\ZfAnnotations\Events;
use Zend\Mvc\MvcEvent;
use Desyncr\ZfAnnotations\Handlers\Events;

class Dispatch extends AbstractEvent {
    protected $event = 'Dispatch';
}
