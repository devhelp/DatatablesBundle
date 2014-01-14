<?php

namespace Devhelp\DatatablesBundle\Lib;

interface DatatablesInterface
{
    public function loadGridConfiguration($grid);

    public function getResult();

    public function buildFilterQuery();

    public function buildOrderQuery();

    public function setRecordsPerPage($recordsPerPage);

    public function getRecordsPerPage();

    public function setOrderBy($orderBy);

    public function getOrderBy();

    public function setOrderType($orderType);

    public function getOrderType();

    public function getQuery();

    public function setQuery($query);

}
