<?php

namespace Devhelp\DatatablesBundle\Lib;

/**
 * Interface DatatablesInterface
 * @package Devhelp\DatatablesBundle\Lib
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

    /**
     * @param $recordsPerPage
     * @return mixed
     */
    public function setRecordsPerPage($recordsPerPage);

    /**
     * @return mixed
     */
    public function getRecordsPerPage();

    /**
     * @param $orderBy
     * @return mixed
     */
    public function setOrderBy($orderBy);

    /**
     * @return mixed
     */
    public function getOrderBy();

    /**
     * @param $orderType
     * @return mixed
     */
    public function setOrderType($orderType);

    /**
     * @return mixed
     */
    public function getOrderType();

    /**
     * @return mixed
     */
    public function getModel();

    /**
     * @param $model
     * @return mixed
     */
    public function setModel($model);

}
