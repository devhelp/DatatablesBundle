<?php

namespace Devhelp\DatatablesBundle\Repository;

use Symfony\Component\HttpFoundation\Request;

/**
 * Entity repository interface
 *
 * @author <michal@devhelp.pl>
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

    /**
     * @param Request $request
     * @return mixed
     */
    public function buildFinalQuery(Request $request);

}