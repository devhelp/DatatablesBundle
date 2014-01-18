<?php

namespace Devhelp\DatatablesBundle\Lib;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Acl\Exception\Exception;

class Datatables extends AbstractDatatables
{

    /**
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
        parent::__construct($paginator, $serializer, $entityManager, $request, $defaultRecordsPerPage, $grids);

    }

    /**
     * @param $grid
     * @throws \Symfony\Component\Security\Acl\Exception\Exception
     */
    public function loadGridConfiguration($grid)
    {
        if (array_key_exists($grid, $this->configuredGrids)) {
            $this->currentGrid = $this->configuredGrids[$grid];
            $this->setModel($this->currentGrid["model"]);
            $this->setQueryBuilder($this->entityManager->getRepository($this->getModel())->getBaseQuery());
            if ($this->currentGrid["default_per_page"]) {
                $this->setRecordsPerPage($this->currentGrid["default_per_page"]);
            }
        } else {
            throw new Exception("Grid not found");
        }
    }

    /**
     * @return mixed
     */
    public function getResult()
    {

        $requestParams = $this->buildRequestParams();

        $current_page = floor(
                $this->request->query->get("iDisplayStart", 0) / $this->request->query->get("iDisplayLength", 1)
            ) + 1;
        $pagination = $this->paginator->paginate(
            $this->getQueryBuilder(),
            $current_page,
            $this->recordsPerPage
        );
        $outputHeader = array(
            "sEcho" => intval($this->request->query->get("sEcho")),
            "iTotalRecords" => $this->entityManager->getRepository($this->getModel())->getTotalRowsCount(),
            "iTotalDisplayRecords" => $pagination->getTotalItemCount(),
            'filters' => $requestParams,
            "aaData" => array()
        );

        foreach ($pagination as $item) {
            $this->output["aaData"][] = $item;
        };

        $this->output = array_merge($outputHeader, $this->output);
        $json = $this->serializer->serialize($this->output, "json");

        return $json;
    }

    /**
     * @return array
     */
    public function buildRequestParams()
    {
        $filtering = array();
        $sorting = array();
        if ($sColumns = $this->request->query->get("sColumns")) {
            $sColumns = array_filter(
                explode(',', $sColumns),
                function ($value) {
                    return !empty($value) || $value === 0;
                }
            );
            $columnLength = count($sColumns);

            $sSearch = $this->request->query->get("sSearch","");

            if($sSearch) {
                $orX =  $this->getQueryBuilder()->expr()->orX();
                for ($i = 0; $i < $columnLength; $i++) {
                    $orX->add($sColumns[$i] ." LIKE '%" . $sSearch . "%'");
                }
                $this->getQueryBuilder()->add('where', $orX);
            }
            for ($i = 0; $i < $columnLength; $i++) {
                $column = $this->request->query->get("sSearch_" . $i);
                $columnSearchable = (int)$this->request->query->get("bSearchable_" . $i);
                if ($column and $columnSearchable) {
                    $filtering[$sColumns[$i]] = $column;
                    $this->getQueryBuilder()->andWhere($this->getQueryBuilder()->expr()->like($sColumns[$i], ":col" . $i))
                        ->setParameter("col" . $i, '%' . $column . '%');
                }
            }
            $iSortingCols = (int)$this->request->query->get("iSortingCols",0);
            if($iSortingCols) {
                for ($i = 0; $i < $iSortingCols; $i++) {
                    $column = (int)$this->request->query->get("iSortCol_" . $i);
                    $order = $this->request->get("sSortDir_".$i);
                    if ($column !== false and $order) {
                        $sorting[$sColumns[$column]] = $order;
                        $this->getQueryBuilder()->addOrderBy($sColumns[$column], $order);
                    }
                }
            } else {
                if ($this->getOrderBy() and $this->getOrderType()) {
                    $this->getQueryBuilder()->addOrderBy($this->getOrderBy(), $this->getOrderType());
                }
            }
        }
        return array('filtering' => $filtering, 'sorting' => $sorting, 'searching' => $sSearch);
    }

}
