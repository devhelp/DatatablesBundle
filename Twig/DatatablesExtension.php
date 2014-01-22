<?php

namespace Devhelp\DatatablesBundle\Twig;


/**
 * Class DatatablesExtension
 * @package Devhelp\DatatablesBundle\Twig
 */
class DatatablesExtension extends \Twig_Extension
{
    /**
     * @var
     */
    public $container;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'render_datatables_grid' => new \Twig_Function_Method($this, 'render', array(
                    'is_safe' => array('html')
                ))
        );
    }

    /**
     * @param $grid
     * @return mixed
     */
    public function render($grid)
    {
        $datatables = $this->container->get('devhelp.datatables');
        $datatables->load($grid);
        $config = $datatables->getCurrentGrid();

        return $this->container->get('templating')->render(
            'DevhelpDatatablesBundle::devhelp_datatables.html.twig',
            array('config' => $config, 'id' => $grid)
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datatables_extension';
    }
}