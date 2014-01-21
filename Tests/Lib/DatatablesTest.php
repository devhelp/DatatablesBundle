<?php

// src/Acme/DemoBundle/Tests/Utility/CalculatorTest.php

namespace Devhelp\DatatablesBundle\Tests\Lib;

use Devhelp\DatatablesBundle\Lib\Datatables;
use Devhelp\DemoBundle\Entity\ProductRepository;
use Doctrine\ORM\QueryBuilder;
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

    /**
     *
     */
    public function setUp()
    {
        $pagination = $this->getPaginatorMock();
        $serializer = $this->getSerializerMock();
        $em = $this->getEmMock();
        $request = new Request($this->setQueryRequest());
        $default_per_page = 10;
        $this->datatables = new Datatables($pagination, $serializer, $em, $request, $default_per_page, $this->getConfiguredGrid()['grids']);
        $this->datatables->loadGridConfiguration('simple_grid');
    }

    /**
     * @return array
     */
    public function getConfiguredGrid()
    {
        return array(
            'default_per_page' => 10,
            'grids' => array(
                'simple_grid' => array(
                    'model' => 'Devhelp\DatatablesBundle\Tests\Entity\Demo',
                    'use_filters' => true,
                    'default_per_page' => 10,
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
                            'bSearchable' => 1,
                            'sName' => 'p.name'
                        ),
                    )
                ),
            )
        );
    }

    /**
     *
     */
    public function testloadGridConfiguration()
    {
        $this->assertEquals($this->getConfiguredGrid()['default_per_page'], 10);
        $this->assertEquals($this->getConfiguredGrid()['grids']['simple_grid'], $this->datatables->getCurrentGrid());
        $this->assertEquals($this->getConfiguredGrid()['grids']['simple_grid']['model'], $this->datatables->getModel());
        $this->assertEquals($this->getConfiguredGrid()['grids']['simple_grid']['use_filters'], true);
        $this->assertEquals($this->getConfiguredGrid()['grids']['simple_grid']['default_per_page'], $this->datatables->getRecordsPerPage());
        $this->assertEquals($this->getConfiguredGrid()['grids']['simple_grid']['order_by'], $this->datatables->getOrderBy());
        $this->assertTrue($this->datatables->getQueryBuilder() instanceof QueryBuilder);

    }

    public function testBuildFilterQuery()
    {
        $result = $this->datatables->buildRequestParams();

        print_r($result);exit;


    }

    public function testGetResult()
    {

    }




    public function setQueryRequest()
    {
        return array(
            'sColumns'=>'p.id,p.name,p.description,p.price,c.name',
            'sSearch' => 'test',
            'sSearch_0' => '2',
            'sSearch_1' => '',
            'sSearch_2' => '',
            'sSearch_3' => '',
        );
    }

    /**
     * @return mixed
     */
    protected function getSerializerMock()
    {
        $mock = $this->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return mixed
     */
    protected function getEmMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('getRepository')
            ->with('Devhelp\DatatablesBundle\Tests\Entity\Demo')
            ->will($this->returnValue($this->getDatatablesRepositoryMock()));

        return $mock;
    }

    /**
     * @return mixed
     */
    protected function getPaginatorMock()
    {
        $mock = $this->getMockBuilder('Knp\Component\Pager\Paginator')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    protected function getDatatablesRepositoryMock()
    {
        $mock = $this->getMockBuilder('Devhelp\DatatablesBundle\Tests\Entity\DemoRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('getBaseQuery')
            ->will($this->returnValue($this->getQueryBuilderMock()));

        $mock->expects($this->any())
            ->method('getTotalRowsCount')
            ->will($this->returnValue(1));

        return $mock;
    }

    /**
     * @return mixed
     */
    protected function getQueryBuilderMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('expr')
            ->will($this->returnValue($this->getExprMock()));

        return $mock;
    }

    protected function getExprMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\Query\Expr')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('orX')
            ->will($this->returnValue($this->getOrXMock()));

        return $mock;
    }

    protected function getOrXMock() {
        $mock = $this->getMockBuilder('Doctrine\ORM\Query\Expr\Orx')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;


    }

}
