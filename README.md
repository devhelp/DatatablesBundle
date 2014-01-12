DatatableBundle
================================

This bundle provides a simple integration of [datatable](http://datatables.net/).

Configuration
------------
    devhelp_datatable:
        default_per_page: 10
        grids:
            product_grid: { sql: 'SELECT p.id, p.name FROM DevhelpDemoBundle:Product p', default_per_page: 2, order_by: 'p.name', order_type: 'desc' }

