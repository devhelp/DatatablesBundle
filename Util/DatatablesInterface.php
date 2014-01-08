<?php

namespace Devhelp\DatatableBundle\Util;

interface DatatablesInterface
{
    public function setPerPage($per_page);
    
    public function setQuery($query);
    
    public function buildFilterQuery();
    
    public function getJsonResult();
    
}
