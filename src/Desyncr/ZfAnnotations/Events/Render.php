<?php
namespace Desyncr\ZfAnnotations\Events;
use Zend\Mvc\MvcEvent;
use Desyncr\ZfAnnotations\Handlers\Events;

class Render extends AbstractEvent {
    protected $event = 'Render';
}
