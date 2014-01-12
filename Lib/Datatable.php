<?php

namespace Devhelp\DatatableBundle\Lib;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Acl\Exception\Exception;

class Datatable extends AbstractDatatable
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
        parent::__construct($paginator, $serializer, $entityManager, $request, $defaultRecordsPerPage, $grids);

    }

    public function loadGridConfiguration($grid)
    {
        if (array_key_exists($grid, $this->configuredGrids)) {
            $this->currentGrid = $this->configuredGrids[$grid];
            $this->setQuery($this->currentGrid["sql"]);
            if ($this->currentGrid["default_per_page"]) {
                $this->setRecordsPerPage($this->currentGrid["default_per_page"]);
            }
            if ($this->currentGrid["order_by"]) {
                $this->setOrderBy($this->currentGrid["order_by"]);
            }
            if ($this->currentGrid["order_type"]) {
                $this->setOrderType($this->currentGrid["order_type"]);
            }
        } else {
            throw new Exception("Grid not found");
        }
    }

    public function getResult()
    {
        $filteringArray = $this->buildFilterQuery();
        $this->buildOrderQuery();
        $current_page = round(
                $this->request->query->get("iDisplayStart", 0) / $this->request->query->get("iDisplayLength", 1)
            ) + 1;
        $pagination = $this->paginator->paginate(
            $this->entityManager->createQuery($this->query),
            $current_page,
            $this->recordsPerPage
        );

        $outputHeader = array(
            "sEcho" => intval($this->request->query->get("sEcho")),
            "iTotalRecords" => $pagination->getTotalItemCount(),
            "iTotalDisplayRecords" => $pagination->getTotalItemCount(),
            'filters' => $filteringArray,
            "aaData" => array()
        );

        foreach ($pagination as $item) {
            $this->output["aaData"][] = $item;
        };

        $this->output = array_merge($outputHeader, $this->output);

        $json = $this->serializer->serialize($this->output, "json");

        return $json;
    }

    public function buildFilterQuery()
    {
        $filteringArray = array();

        if ($sColumns = $this->request->query->get('sColumns')) {
            $sColumns = array_filter(
                explode(',', $sColumns),
                function ($value) {
                    return !empty($value) || $value === 0;
                }
            );
            $columnLenght = count($sColumns);
            for ($i = 0; $i < $columnLenght; $i++) {
                $filteringArray[$sColumns[$i]] = $this->request->query->get("sSearch_" . $i);
                $this->query .= " AND " . $sColumns[$i] . " LIKE '%" . $this->request->query->get(
                        'sSearch_' . $i
                    ) . "%'";
            }
        }

        return $filteringArray;
    }

    public function buildOrderQuery()
    {
        if ($this->getOrderBy() and $this->getOrderType()) {
            $this->query .= " ORDER BY " . $this->getOrderBy() . " " . $this->getOrderType();
        }
    }

}
