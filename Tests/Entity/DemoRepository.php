<?php

namespace Devhelp\DatatablesBundle\Tests\Entity;

use Devhelp\DatatablesBundle\Lib\AbstractDatatablesRepository;

class DemoRepository extends AbstractDatatablesRepository
{

    public function getBaseQuery()
    {
        return 1;
    }

    public function getTotalRowsCount()
    {

    }
}