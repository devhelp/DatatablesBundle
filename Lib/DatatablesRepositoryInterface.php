<?php

namespace Devhelp\DatatablesBundle\Lib;


interface DatatablesRepositoryInterface {

    public function getBaseQuery();

    public function getTotalRowsCount();

}