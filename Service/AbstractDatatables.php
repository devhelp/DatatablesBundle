<?php

namespace Devhelp\DatatablesBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * AbstractDatatables class implements common method for all
 * datables instance object
 *
 * @author <michal@devhelp.pl>
 */
abstract class AbstractDatatables
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
     * @param PaginatorInterface $paginator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param $defaultRecordsPerPage
     * @param $grids
     */
    public function __construct(
        PaginatorInterface $paginator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
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
     * @return mixed
     */
    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

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
