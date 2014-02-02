<?php
/**
 * Events\Render
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
namespace Desyncr\Annotations\Events;

/**
 * Class Render
 *
 * @category General
 * @package  Desyncr\Annotations\Events
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://me.syncr.com.ar
 */
class Render extends AbstractEvent
{
    /**
     * @var string
     */
    protected $event = 'Render';
}
