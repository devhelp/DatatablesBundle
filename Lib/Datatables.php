<?php

namespace Devhelp\DatatablesBundle\Lib;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 * Class Datatables
 * @package Devhelp\DatatablesBundle\Lib
 */
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
     *
     * @param $grid
     * @throws \Symfony\Component\Security\Acl\Exception\Exception
     */
    public function load($grid)
    {

        if (array_key_exists($grid, $this->configuredGrids)) {
            $this->currentGrid = $this->configuredGrids[$grid];
            $this->setModel($this->currentGrid["model"]);
            $this->setOrderBy($this->currentGrid['order_by']);
            $this->setOrderType($this->currentGrid['order_type']);
            $this->setRecordsPerPage($this->currentGrid["default_per_page"]);
        } else {
            throw new Exception("Grid not found");
        }
    }

    /**
     *
     * @return string
     */
    public function getResult()
    {

        $finalQuery = $this->entityManager->getRepository($this->getModel())->buildFinalQuery($this->request, $this->getOrderBy(), $this->getOrderType());

        $current_page = floor(
                $this->request->query->get("iDisplayStart", 0) / $this->request->query->get("iDisplayLength", 1)
            ) + 1;


        $pagination = $this->paginator->paginate(
            $finalQuery,
            $current_page,
            $this->getRecordsPerPage()
        );

        $outputHeader = array(
            "sEcho" => intval($this->request->query->get("sEcho")),
            "iTotalRecords" => $this->entityManager->getRepository($this->getModel())->getTotalRowsCount(),
            "iTotalDisplayRecords" => $pagination->getTotalItemCount(),
            "aaData" => array()
        );

        foreach ($pagination->getItems() as $item) {
            $this->output["aaData"][] = $item;
        };
        $this->output = array_merge($outputHeader, $this->output);
        $json = $this->serializer->serialize($this->output, "json");

        return $json;
    }


}
