DatatableBundle
================================

This bundle provides a simple integration of [Datatables](http://datatables.net/).

Installation
------------
using composer.json

    "require": {
        "devhelp/datatable-bundle": "dev-master"
    },

Configuration
------------
    #config.yml
    devhelp_datatable:
        default_per_page: 10
        grids:
            product_grid: { sql: 'SELECT p.id, p.name FROM DevhelpDemoBundle:Product p', default_per_page: 2, order_by: 'p.name', order_type: 'desc' }

    #AppKernel.php
    $bundles = array(
        ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Devhelp\DatatableBundle\DevhelpDatatableBundle(),
        ...
    )

Usage
------------
    public function indexAction()
    {
        $grid = $this->get('datatables');
        //$datatables->setQuery('SELECT p FROM DevhelpDemoBundle:Product p');
        //or
        $grid->loadGridConfiguration('product_grid');

        $jsonResult =  $grid->getResult();
        return new Response($jsonResult);
    }