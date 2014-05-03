#DatatablesBundle
[![Build Status](https://travis-ci.org/devhelp/DatatablesBundle.png?branch=master)](https://travis-ci.org/devhelp/DatatablesBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bc9237da-698a-417f-8e69-ab11b61f6811/big.png)](https://insight.sensiolabs.com/projects/bc9237da-698a-417f-8e69-ab11b61f6811)

This bundle provides a simple integration of [Datatables](http://datatables.net/).

##Installation
using composer.json
```javascript
    "require": {
        "devhelp/datatables-bundle": "dev-master"
    },
```
```cli
    php app/console assetic:dump
```

##Configuration

###Create configuration

#####config.yml
```yaml

    assetic:
        ...
        bundles:        [ DevhelpDatatablesBundle ]
        ...
```
#####Minimal configuration
#####config.yml
```yaml
    devhelp_datatables:
        default_per_page: 10
        grids:
            product_grid:
                model: Devhelp\DemoBundle\Entity\Product
                routing: get_grid
                colum   ns:
                    - { title: 'ID',          data: 'id',            alias : 'p.id' }
                    - { title: 'Name',        data: 'name',          alias : 'p.name' }
                    - { title: 'Description', data: 'description',   alias : 'p.description' }
                    - { title: 'Price',       data: 'price',         alias : 'p.price' }
                    - { title: 'Category',    data: 'category.name', alias : 'c.name'}
```

#####Full configuration
#####config.yml
```yaml
    devhelp_datatables:
        default_per_page: 10
        grids:
            product_grid:
                model: Devhelp\DemoBundle\Entity\Product
                routing: get_grid
                use_filters: true
                default_per_page: 10
                columns:
                    - { title: 'ID',          data: 'id',            alias : 'p.id',          searchable: 1, visible: 1, width: "10%" }
                    - { title: 'Name',        data: 'name',          alias : 'p.name',        searchable: 1, visible: 1, width: "30%" }
                    - { title: 'Description', data: 'description',   alias : 'p.description', searchable: 0, visible: 1, width: "10%" }
                    - { title: 'Price',       data: 'price',         alias : 'p.price',       searchable: 1, visible: 1, width: "10%" }
                    - { title: 'Category',    data: 'category.name', alias : 'c.name',        searchable: 1, visible: 1, width: "60%" }
```



#####AppKernel.php
```php
    $bundles = array(
        ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Devhelp\DatatablesBundle\DevhelpDatatablesBundle(),
        ...
    )
```
###Create entities and repository class

#####Product.php
```php
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
```
#####Category.php
```php
    namespace Devhelp\DemoBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="category")
     */
    class Category
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

    }
```
#####ProductRepository.php
```php
    namespace Devhelp\DemoBundle\Entity;

    use Devhelp\DatatablesBundle\Lib\AbstractDatatablesRepository;

    class ProductRepository extends AbstractDatatablesRepository
    {

        public function getBaseQuery()
        {
            return $this->createQueryBuilder('p')->leftJoin('p.category','c');
        }

    }
````

##Usage
#####Controller
```php
    /**
    *
    * @Route("/grid", name="product_grid")
    */
    public function indexAction()
    {
        $grid = $this->get('devhelp.datatables');
        $grid->load('product_grid');

        $jsonResult =  $grid->getResult();
        return new Response($jsonResult);
    }
```
#####View
```twig
    {{ render_datatables_grid('product_grid') }}
```

