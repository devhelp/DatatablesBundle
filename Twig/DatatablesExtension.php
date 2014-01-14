<?php

namespace Devhelp\DatatablesBundle\Twig;


class DatatablesExtension extends \Twig_Extension
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'devhelp_datatables' => new \Twig_Function_Method($this, 'devhelpDatatables', array(
                    'is_safe' => array('html')
                ))
        );
    }

    public function devhelpDatatables($grid)
    {
        $datatables = $this->container->get('devhelp.datatables');
        $datatables->loadGridConfiguration($grid);
        $config = $datatables->getCurrentGrid();

        return $this->container->get('templating')->render(
            'DevhelpDatatablesBundle::devhelp_datatables.html.twig',
            array('columns' => $config['columns'], 'id' => $grid)
        );
    }

    public function getName()
    {
        return 'datatables_extension';
    }
}