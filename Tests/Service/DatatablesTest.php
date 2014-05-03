<?php

namespace Devhelp\DatatablesBundle\Tests\Service;

use Devhelp\DatatablesBundle\Service\Datatables;
use Symfony\Component\HttpFoundation\Request;

class DatatablesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Devhelp\DatatablesBundle\Service\Datatables
     */
    public $datatables;

    /**
     * @var array
     */
    public $array;

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
        $config = $this->getConfiguredGrid();
        $this->datatables = new Datatables($pagination, $serializer, $em, $request, $default_per_page, $config['grids']);

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
                    'model' => 'Devhelp\DatatablesBundle\Tests\Fixtures\Entity\Test',
                    'routing' => 'test',
                    'use_filters' => true,
                    'default_per_page' => 10,
                    'order_by' => 'p.name',
                    'order_type' => 'asc',
                    'columns' => array(
                        0 => array(
                            'title' => 'ID',
                            'data' => 'id',
                            'alias' => 'p.id'
                        ),
                        1 => array(
                            'title' => 'ID',
                            'data' => 'id',
                            'alias' => 'p.id'
                        ),
                    )
                ),
            )
        );
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $array = array();
        $array[] = array('id' => 1, 'name' => 'test1');
        $array[] = array('id' => 2, 'name' => 'test2');
        $array[] = array('id' => 3, 'name' => 'test3');
        $array[] = array('id' => 4, 'name' => 'test4');
        $array[] = array('id' => 5, 'name' => 'test5');
        return $array;
    }

    /**
     * @return array
     */
    public function getOutputHeader()
    {
        $outputHeader = array(
            "sEcho" => 1,
            "iTotalRecords" => 5,
            "iTotalDisplayRecords" => 5,
            "aaData" => array()
        );
        return $outputHeader;
    }

    /**
     *
     */
    public function testLoad()
    {
        $this->datatables->load('simple_grid');
        $grid = $this->getConfiguredGrid();
        $this->assertEquals($grid['default_per_page'], 10);
        $this->assertEquals($grid['grids']['simple_grid'], $this->datatables->getCurrentGrid());
        $this->assertEquals($grid['grids']['simple_grid']['model'], $this->datatables->getModel());
        $this->assertEquals($grid['grids']['simple_grid']['use_filters'], true);
        $this->assertEquals($grid['grids']['simple_grid']['default_per_page'], $this->datatables->getRecordsPerPage());

    }

    /**
     *
     */
    public function testGetResult()
    {
        $this->datatables->load('simple_grid');
        $result = $this->datatables->getResult();


        $items['aaData'] = $this->getItems();
        $output = array_merge($this->getOutputHeader(), $items);


        $this->assertEquals(serialize($output), $result);

    }

    /**
     * @return array
     */
    public function setQueryRequest()
    {
        return array(
            'sColumns' => 'p.id,p.name,p.description,p.price,c.name',
            'sSearch' => 'test',
            'sSearch_0' => '2',
            'sSearch_1' => '',
            'sSearch_2' => '',
            'sSearch_3' => '',
            'iDisplayStart' => 1,
            'iDisplayLength' => 10,
            'sEcho' => 1
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

        $items['aaData'] = $this->getItems();
        $output = array_merge($this->getOutputHeader(), $items);

        $mock->expects($this->any())
            ->method('serialize')
            ->with($output)
            ->will($this->returnValue(serialize($output)));

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
            ->with('Devhelp\DatatablesBundle\Tests\Fixtures\Entity\Test')
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

        $mock->expects($this->any())
            ->method('paginate')
            ->will($this->returnValue($this->getSlidingPaginationMock()));

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
            ->will($this->returnValue(5));

        $mock->expects($this->any())
            ->method('getItems')
            ->will($this->returnValue($this->getItems()));


        return $mock;
    }

    /**
     * @return mixed
     */
    protected function getDatatablesRepositoryMock()
    {
        $mock = $this->getMockBuilder('Devhelp\DatatablesBundle\Tests\Fixtures\Repository\TestRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('buildFinalQuery')
            ->will($this->returnValue($this->getQueryBuilderMock()));

        $mock->expects($this->any())
            ->method('getTotalRowsCount')
            ->will($this->returnValue(5));

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

        return $mock;
    }

}
