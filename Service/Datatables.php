<?php

namespace Devhelp\DatatablesBundle\Service;

/**
 * Main class contains final methods for datatables
 *
 * @author <michal@devhelp.pl>
 */
class Datatables extends AbstractDatatables implements DatatablesInterface
{

    /**
     * Load configuration
     *
     * @param $grid
     * @return mixed|void
     * @throws \Exception
     */
    public function load($grid)
    {
        if (array_key_exists($grid, $this->configuredGrids)) {
            $this->currentGrid = $this->configuredGrids[$grid];
            $this->setModel($this->currentGrid["model"]);
            $this->setRecordsPerPage($this->currentGrid["default_per_page"]);
        } else {
            throw new \Exception("Grid not found");
        }
    }

    /**
     * Get records from final query and paging and serialize result
     *
     * @return mixed|string
     */
    public function getResult()
    {

        $finalQuery = $this->entityManager->getRepository($this->getModel())->buildFinalQuery($this->request);

        $current_page = floor(
                $this->request->query->get("iDisplayStart", 0) / $this->request->query->get("iDisplayLength", 1)
            ) + 1;

        $pagination = $this->paginator->paginate(
            $finalQuery,
            $current_page,
            $this->request->query->get("iDisplayLength", 1)
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
