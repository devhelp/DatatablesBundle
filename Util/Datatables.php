<?php

namespace Devhelp\DatatableBundle\Util;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use Devhelp\DatatableBundle\Util\DatatablesInterface;

class Datatables implements DatatablesInterface
{

    protected $paginator;
    protected $request;
    protected $query;
    protected $serializer;
    protected $per_page;
    protected $em;

    public function __construct(Paginator $paginator, Serializer $serializer, EntityManager $em, Request $request = null, $default_per_page)
    {
        $this->paginator = $paginator;
        $this->serializer = $serializer;
        $this->per_page = $default_per_page;
        $this->request = $request;
        $this->em = $em;
        $this->output = array();
    }

    public function setPerPage($per_page)
    {
        $this->per_page = $per_page;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
    
    public function buildFilterQuery()
    {
        $filteringArray = array();

        if($sColumns = $this->request->query->get('sColumns')) {
            $sColumns = array_filter(explode(',', $sColumns),array('self','_remove_empty_internal'));
            $columnLenght = count($sColumns);
            for ($i = 0; $i < $columnLenght; $i++) {
                $filteringArray[$sColumns[$i]] = $this->request->query->get('sSearch_' . $i);
                $this->query .= " and " . $sColumns[$i] . " LIKE '%" . $this->request->query->get('sSearch_' . $i) . "%'";
            }
        }
        
        return $filteringArray;
    }

    public function getJsonResult()
    {   
        $filteringArray = $this->buildFilterQuery();
        $current_page = round($this->request->query->get('iDisplayStart', 0) / $this->request->query->get('iDisplayLength', 1)) + 1;
        $pagination = $this->paginator->paginate(
                $this->em->createQuery($this->query), $current_page, $this->per_page
        );
        
        $outputHeader = array(
            "sEcho" => intval($this->request->query->get('sEcho')),
            "iTotalRecords" => $pagination->getTotalItemCount(),
            "iTotalDisplayRecords" => $pagination->getTotalItemCount(),
            'filters' => $filteringArray,
            "aaData" => array()
        );
        
        foreach ($pagination as $item) {
             $this->output['aaData'][] = $item;
        };

        $this->output = array_merge($outputHeader,  $this->output);
         
        $json = $this->serializer->serialize( $this->output, 'json');
       
        return $json;
    }

    private static function _remove_empty_internal($value) {
        return !empty($value) || $value === 0;
    }

}
