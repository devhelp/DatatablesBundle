<?php

namespace Devhelp\DatatableBundle\Twig;


class DatatableExtension extends \Twig_Extension
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'devhelp_datatable' => new \Twig_Function_Method($this, 'devhelpDatatable', array(
                    'is_safe' => array('html')
                ))
        );
    }

    public function devhelpDatatable($grid)
    {
        $datatables = $this->container->get('datatables');
        $datatables->loadGridConfiguration($grid);
        $config = $datatables->getCurrentGrid();

        return $this->container->get('templating')->render(
            'DevhelpDatatableBundle::devhelp_datatable.html.twig',
            array('columns' => $config['columns'], 'id' => $grid)
        );
    }

    public function getName()
    {
        return 'datatable_extension';
    }
}