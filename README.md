DatatablesBundle
================================

This bundle provides a simple integration of [Datatables](http://datatables.net/).

Installation
------------
using composer.json

    "require": {
        "devhelp/datatables-bundle": "dev-master"
    },

Configuration
------------

Create configuration
============
    #config.yml
    devhelp_datatables:
        default_per_page: 10
        grids:
            product_grid:
                model: Devhelp\DemoBundle\Entity\Product
                default_per_page: 10
                order_by: 'p.name'
                order_type: 'asc'
                columns:
                    - { mData: 'id', bSearchable: 1, sName : 'p.id' }
                    - { mData: 'name', bSearchable: 1, sName : 'p.name' }
                    - { mData: 'description', bSearchable: 1, sName : 'p.description' }
                    - { mData: 'price', bSearchable: 1, sName : 'p.price' }
                    - { mData: 'category.name', bSearchable: 1, sName : 'c.name' }

    #AppKernel.php
    $bundles = array(
        ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Devhelp\DatatablesBundle\DevhelpDatatablesBundle(),
        ...
    )

Create simple entity and repository class
=========================================

    #Product.php
    namespace Devhelp\DemoBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity(repositoryClass="Devhelp\DemoBundle\Entity\ProductRepository")
     * @ORM\Table(name="product")
     *
     */
    class Product
    {
        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\Column(type="string", length=100)
         */
        protected $name;

        /**
         * @ORM\Column(type="decimal", scale=2)
         */
        protected $price;

        /**
         * @ORM\Column(type="text")
         */
        protected $description;

        /**
         * @ORM\ManyToOne(targetEntity="Category")
         * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
         */
        protected $category;
    }

    #ProductRepository
    namespace Devhelp\DemoBundle\Entity;

    use Devhelp\DatatablesBundle\Lib\AbstractDatatablesRepository;

    class ProductRepository extends AbstractDatatablesRepository
    {

        public function getBaseQuery()
        {
            return $this->createQueryBuilder('p')->leftJoin('p.category','c');
        }

        public function getTotalRowsCount()
        {
            return count($this->createQueryBuilder('p')->leftJoin('p.category','c')->getQuery()->getScalarResult());
        }
    }


Usage
------------
    public function indexAction()
    {
        $grid = $this->get('devhelp.datatables');
        $grid->loadGridConfiguration('product_grid');

        $jsonResult =  $grid->getResult();
        return new Response($jsonResult);
    }