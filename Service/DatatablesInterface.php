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
