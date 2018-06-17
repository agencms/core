<?php

namespace Agencms\Core\Contracts;

interface RepeaterHandlerContract
{
    /**
     * Define the default repeater elements. Overwrite this method to change the default groups.
     *
     * Must return an Array of Agencms\Core\Group objects.
     *
     * @return array
     */
    public function getDefaultRepeaters();

    /**
     * Include additional repeater groups before the default groups.
     *
     * Must return an Array of Agencms\Core\Group objects.
     *
     * @return array
     */
    public function getPrependRepeaters();

    /**
     * Include additional repeater groups after the default groups.
     *
     * Must return an Array of Agencms\Core\Group objects.
     *
     * @return array
     */
    public function getAppendRepeaters();

    /**
     * Retuns an array of repeater groups from the three repeater methods
     *
     * Must return an Array of Agencms\Core\Group objects.
     *
     * @return array
     */
    public static function get();
}
