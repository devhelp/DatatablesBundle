<?php

namespace Devhelp\DatatablesBundle\Lib;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

abstract class AbstractDatatables implements DatatablesInterface
{
    protected $paginator;
    protected $request;
    protected $query;
    protected $serializer;
    protected $recordsPerPage;
    protected $entityManager;
    protected $currentGrid;
    protected $orderBy;
    protected $orderType;

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

    abstract public function loadGridConfiguration($grid);

    abstract public function getResult();

    abstract public function buildFilterQuery();

    abstract public function buildOrderQuery();

    public function setRecordsPerPage($recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;
    }

    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
    }

    public function getOrderType()
    {
        return $this->orderType;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getCurrentGrid()
    {
        return $this->currentGrid;
    }

    public function setCurrentGrid($grid)
    {
        $this->currentGrid = $grid;
    }

}
