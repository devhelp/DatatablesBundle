<?php

namespace Devhelp\DatatablesBundle\Lib;


/**
 * Interface DatatablesRepositoryInterface
 * @package Devhelp\DatatablesBundle\Lib
 */
interface DatatablesRepositoryInterface {

    /**
     * @return mixed
     */
    public function getBaseQuery();

    /**
     * @return mixed
     */
    public function getTotalRowsCount();

}