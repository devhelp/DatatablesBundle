<?php

namespace Devhelp\DatatablesBundle\Lib;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractDatatablesRepository
 * @package Devhelp\DatatablesBundle\Lib
 */
abstract class AbstractDatatablesRepository extends EntityRepository implements DatatablesRepositoryInterface
{

    /**
     * @return mixed
     */
    abstract public function getBaseQuery();

    /**
     * @return mixed
     */
    public function getTotalRowsCount()
    {
        return $this->getBaseQuery()->select('COUNT(p.id)')->getQuery()->getSingleScalarResult();

    }

    /**
     * @param Request $request
     * @param null $default_order_by
     * @param null $default_order_type
     * @return mixed
     */
    public function buildFinalQuery(Request $request, $default_order_by = null, $default_order_type = null)
    {
        $finalQuery = $this->getBaseQuery();

        $filtering = array();
        $sorting = array();
        $sSearch = $request->query->get("sSearch", "");
        if ($sColumns = $request->query->get("sColumns")) {

            $sColumns = array_filter(
                explode(',', $sColumns),
                function ($value) {
                    return !empty($value) || $value === 0;
                }
            );
            $columnLength = count($sColumns);

            if ($sSearch) {
                $orX = $finalQuery->expr()->orX();
                for ($i = 0; $i < $columnLength; $i++) {
                    $orX->add($sColumns[$i] . " LIKE '%" . $sSearch . "%'");
                }
                $finalQuery->add('where', $orX);
            }
            for ($i = 0; $i < $columnLength; $i++) {
                $column = $request->query->get("sSearch_" . $i);
                $columnSearchable = (int)$request->query->get("bSearchable_" . $i);
                if ($column && $columnSearchable) {

                    $filtering[$sColumns[$i]] = $column;
                    $finalQuery->andWhere($this->getBaseQuery()->expr()->like($sColumns[$i], ":col" . $i))
                        ->setParameter("col" . $i, '%' . $column . '%');
                }
            }
            $iSortingCols = (int)$request->query->get("iSortingCols", 0);
            if ($iSortingCols) {
                for ($i = 0; $i < $iSortingCols; $i++) {
                    $column = (int)$request->query->get("iSortCol_" . $i);
                    $order = $request->get("sSortDir_" . $i);
                    if ($column !== false && $order) {
                        $sorting[$sColumns[$column]] = $order;
                        $finalQuery->addOrderBy($sColumns[$column], $order);
                    }
                }
            } else {
                if ($default_order_by && $default_order_type) {
                    $finalQuery->addOrderBy($default_order_by, $default_order_type);
                }
            }
        }

        return $finalQuery;
    }
} 