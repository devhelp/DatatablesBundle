<?php

namespace Devhelp\DatatableBundle\Lib;

interface DatatableInterface
{
    public function setRecordsPerPage($recordsPer_page);
    
    public function setQuery($query);
    
    public function buildFilterQuery();
    
    public function getJsonResult();
    
}
