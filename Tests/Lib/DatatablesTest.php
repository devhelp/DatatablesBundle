<?php

// src/Acme/DemoBundle/Tests/Utility/CalculatorTest.php

namespace Devhelp\DatatablesBundle\Tests\Lib;

use Devhelp\DatatablesBundle\Lib\Datatables;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

class DatatablesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Devhelp\DatatablesBundle\Lib\Datatables
     */
    public $datatables;


    public function __construct()
    {
        $pagination = $this->getPaginatorMock();
        $serializer = $this->getSerializerMock();
        $em = $this->getEmMock();
        $request = new Request($this->setQueryRequest());
        $default_per_page = 10;
        $this->datatables = new Datatables($pagination, $serializer, $em, $request, $default_per_page, $this->getConfiguredGrid()['grids']);
        $this->datatables->loadGridConfiguration('product_grid');
    }

    public function testloadGridConfiguration()
    {
        $this->assertEquals($this->getConfiguredGrid()['grids']['product_grid'], $this->datatables->getCurrentGrid());
        $this->assertEquals($this->getConfiguredGrid()['grids']['product_grid']['sql'], $this->datatables->getQuery());
        $this->assertEquals(
            $this->getConfiguredGrid()['grids']['product_grid']['default_per_page'],
            $this->datatables->getRecordsPerPage()
        );

    }

    public function testBuildFilterQuery()
    {

        print_r($this->datatables->buildFilterQuery());

    }

    public function testGetResult()
    {
        $paginator = $this->getSlidingPaginationMock();

       // print_r($paginator->getTotalItemCount());
    }


    public function getConfiguredGrid()
    {
        return array(
            'default_per_page' => 10,
            'grids' => array(
                'product_grid' => array(
                    'sql' => 'SELECT p, c FROM DevhelpDemoBundle:Product p LEFT JOIN p.category c WHERE 1=1',
                    'default_per_page' => 1,
                    'order_by' => 'p.name',
                    'order_type' => 'asc',
                    'columns' => array(
                        0 => array(
                            'mData' => 'id',
                            'bSearchable' => 1,
                            'sName' => 'p.id'
                        ),
                        1 => array(
                            'mData' => 'name',
                            'bSearchable' => 2,
                            'sName' => 'p.id'
                        ),
                    )
                ),
            )
        );
    }

    public function setQueryRequest()
    {
        return array(
            'sColumns'=>'p.id,p.name,p.description,p.price,c.name',
            'sSearch_0' => '2',
            'sSearch_1' => '',
            'sSearch_2' => '',
            'sSearch_3' => '',
        );
    }


    protected function getSerializerMock()
    {
        $mock = $this->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()
            ->getMock();

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
        $mock = $this->getMockBuilder('Knp\Component\Pager\Paginator')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    protected function getSlidingPaginationMock()
    {
        $mock = $this->getMockBuilder('Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('getTotalItemCount')
            ->will($this->returnValue(2));

        return $mock;
    }


}
