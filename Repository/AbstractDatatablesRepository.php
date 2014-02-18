<?php

namespace Devhelp\DatatablesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract repository class contains default methods for sorting and filtering data in grid
 *
 * @author <michal@devhelp.pl>
 */
abstract class AbstractDatatablesRepository extends EntityRepository implements DatatablesRepositoryInterface
{

    /**
     * This functions is responsible for returning number of records
     *
     * @return integer
     */
    public function getTotalRowsCount()
    {
        $alias = current($this->getBaseQuery()->getDQLPart('from'))->getAlias();

        return $this->getBaseQuery()->select("COUNT($alias)")->getQuery()->getSingleScalarResult();

    }

    /**
     * This function is responsible for creating final query with request
     *
     * @param Request $request
     * @return mixed
     */
    public function buildFinalQuery(Request $request)
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
            }
        }

        return $finalQuery;
    }
} 