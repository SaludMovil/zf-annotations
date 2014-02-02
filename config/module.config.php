<?php
/**
 * Desyncr\Annotations configuration
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Annotations
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://me.syncr.com.ar
 */
return array(
    'zf-annotations' => array(
        /**
         * Autoload annotations namespace
         */
        'autoload' => array(),

        /**
         * Events and their respective handlers
         */
        'event_handlers' => array(
            'Desyncr\Annotations\Events\Init'     => 'Desyncr\Annotations\Annotations\Init',
            'Desyncr\Annotations\Events\Dispatch' => 'Desyncr\Annotations\Annotations\Dispatch',
            'Desyncr\Annotations\Events\Render'   => 'Desyncr\Annotations\Annotations\Render',
            'Desyncr\Annotations\Events\Route'    => 'Desyncr\Annotations\Annotations\Route',
        ),
        /**
         * AnnotationReader file cache path
         */
        'cache' => '/tmp/',

        /**
         * AnnotationReader cache debug (set to true for development)
         */
        'debug' => true,
    )
);
