<?php

namespace Devhelp\DatatablesBundle\Lib;

interface DatatablesInterface
{
    public function loadGridConfiguration($grid);

    public function getResult();

    public function buildRequestParams();

    public function setRecordsPerPage($recordsPerPage);

    public function getRecordsPerPage();

    public function setOrderBy($orderBy);

    public function getOrderBy();

    public function setOrderType($orderType);

    public function getOrderType();

    public function getModel();

    public function setModel($model);

}
