<?php

namespace Devhelp\DatatablesBundle\Lib;

use Doctrine\ORM\EntityRepository;

abstract class AbstractDatatablesRepository extends EntityRepository implements DatatablesRepositoryInterface {

    abstract public function getBaseQuery();

    abstract public function getTotalRowsCount();

} 