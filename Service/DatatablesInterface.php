<?php

namespace Devhelp\DatatablesBundle\Service;

/**
 * Datatables interface
 *
 * @author <michal@devhelp.pl>
 */

interface DatatablesInterface
{
    /**
     * @param $grid
     * @return mixed
     */
    public function load($grid);

    /**
     * @return mixed
     */
    public function getResult();


}
