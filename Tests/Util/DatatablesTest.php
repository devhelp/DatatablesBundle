<?php

// src/Acme/DemoBundle/Tests/Utility/CalculatorTest.php

namespace Devhelp\DatatableBundle\Tests\Util;

use Devhelp\DatatableBundle\Util\Datatables;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

class DatatablesTest extends \PHPUnit_Framework_TestCase {

    public function testDatatable() {

        $pagination = $this->getPaginatorMock();
        
        
        $serializer = $this->getSerializerMock();
        $em = $this->getEmMock();
        $request = new Request();
        $default_per_page = 10;
        $query = "SELECT u, g FROM OkinetUserBundle:User u LEFT JOIN u.groups g where 1=1";
        


        $datatables = new Datatables($pagination, $serializer, $em, $request, $default_per_page);
        
        $q = '{"sEcho":0,"iTotalRecords":10,"iTotalDisplayRecords":10,"filters":[],"aaData":[1,2,3,4,5,6,7,8,9,10]}';
        $this->assertEquals($q, $datatables->getJsonResult());
    }

    protected function getSerializerMock() {
        
        
        $q = '{"sEcho":0,"iTotalRecords":10,"iTotalDisplayRecords":10,"filters":[],"aaData":[1,2,3,4,5,6,7,8,9,10]}';
        $mock = $this->getMockBuilder('JMS\Serializer\Serializer')
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())
                ->method('serialize')
                ->will($this->returnValue($q));

        return $mock;
    }

    protected function getEmMock() 
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();

        return $mock;
    }
    
    protected function getPaginatorMock()
    {
        
        $p = new Paginator;

        $items = range(1, 10);
        $view = $p->paginate($items, 1, 10);
        
        $mock = $this->getMockBuilder('Knp\Component\Pager\Paginator')
                ->disableOriginalConstructor()
                ->getMock();
        
        $mock->expects($this->any())
                ->method('paginate')
                ->will($this->returnValue($view));
        
       
        
        return $mock;
    }
            

}
