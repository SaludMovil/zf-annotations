<?php
namespace Desyncr\ZfAnnotations\Events;
use Zend\Mvc\MvcEvent;
use Desyncr\ZfAnnotations\Handlers\Events;

class Init extends AbstractEvent {
    protected $event = 'Init';
}
