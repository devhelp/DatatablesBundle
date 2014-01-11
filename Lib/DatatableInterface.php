<?php

namespace Devhelp\DatatableBundle\Lib;

interface DatatableInterface
{
    public function setRecordsPerPage($recordsPerPage);
    
    public function setQuery($query);
    
    public function buildFilterQuery();
    
    public function getResult();
    
}
