<?php

namespace Devhelp\DatatablesBundle\Lib;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

/**
 * Class AbstractDatatables
 * @package Devhelp\DatatablesBundle\Lib
 */
abstract class AbstractDatatables implements DatatablesInterface
{
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    protected $paginator;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var \JMS\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var integer
     */
    protected $recordsPerPage;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $currentGrid;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $orderType;

    /**
     * s
     * @param Paginator $paginator
     * @param Serializer $serializer
     * @param EntityManager $entityManager
     * @param Request $request
     * @param $defaultRecordsPerPage
     * @param $grids
     */
    public function __construct(
        Paginator $paginator,
        Serializer $serializer,
        EntityManager $entityManager,
        Request $request = null,
        $defaultRecordsPerPage,
        $grids
    ) {
        $this->paginator = $paginator;
        $this->serializer = $serializer;
        $this->recordsPerPage = $defaultRecordsPerPage;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->output = array();
        $this->configuredGrids = $grids;

    }

    /**
     *
     * @param $grid
     * @return mixed
     */
    abstract public function load($grid);

    /**
     *
     * @return mixed
     */
    abstract public function getResult();


    /**
     *
     * @param $recordsPerPage
     */
    public function setRecordsPerPage($recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;
    }

    /**
     *
     * @return mixed
     */
    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

    /**
     *
     * @param $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     *
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     *
     * @param $orderType
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
    }

    /**
     *
     * @return mixed
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     *
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     *
     * @return mixed
     */
    public function getCurrentGrid()
    {
        return $this->currentGrid;
    }

    /**
     *
     * @param array $grid
     */
    public function setCurrentGrid($grid)
    {
        $this->currentGrid = $grid;
    }

}
