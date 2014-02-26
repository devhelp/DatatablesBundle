<?php

namespace Devhelp\DatatablesBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * AbstractDatatables class implements common methods for all
 * datables instance object
 *
 * @author <michal@devhelp.pl>
 */
abstract class AbstractDatatables
{
    /**
     * @var \Knp\Component\Pager\PaginatorInterface
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
     * @var \JMS\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var integer
     */
    protected $recordsPerPage;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
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
     * Get default records per page
     *
     * @return mixed
     */
    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

    /**
     * Set default records per page
     *
     * @param $recordsPerPage
     * @return $this
     */
    public function setRecordsPerPage($recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;
        return $this;
    }


    /**
     * Get model
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get currenct configured grid
     *
     * @return mixed
     */
    public function getCurrentGrid()
    {
        return $this->currentGrid;
    }

    /**
     * Set current configured grid
     *
     * @param array $grid
     * @return AbstractDatatables
     */
    public function setCurrentGrid($grid)
    {
        $this->currentGrid = $grid;
        return $this;
    }

    /**
     * @param mixed $configuredGrids
     * @return AbstractDatatables
     */
    public function setConfiguredGrids($configuredGrids)
    {
        $this->configuredGrids = $configuredGrids;
        return $this;
    }

    /**
     * Get Configured grids
     *
     * @return array
     */
    public function getConfiguredGrids()
    {
        return $this->configuredGrids;
    }

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return AbstractDatatables
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param array $output
     * @return AbstractDatatables
     */
    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     * @return AbstractDatatables
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @return \Knp\Component\Pager\PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return AbstractDatatables
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @return AbstractDatatables
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @return \JMS\Serializer\SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }



}
