<?php
$menuData = array(
    1 => array(
        'title' => 'Admin', 
        'url' => HTTPPATH.'/index.php?page=admin/index',
		'sub' => array(
            11 => array('title' => 'Login', 'url' => HTTPPATH.'/index.php?page=admin/login'),
			12 => array(
				'title' => 'Employees', 
				'url' => '#', 
				'sub' => array(
					121 => array('title' => 'Import List', 'url' => HTTPPATH.'/index.php?page=admin/employee_import_list'),
					122 => array('title' => 'Manual Entry', 'url' => HTTPPATH.'/index.php?page=admin/employee_manual'),
					123 => array('title' => 'List All', 'url' => HTTPPATH.'/index.php?page=admin/employee_list'),
					124 => array('title' => 'Search', 'url' => HTTPPATH.'/index.php?page=admin/employee_search'),
				)
			),
			13 => array(
				'title' => 'Employers', 
				'url' => '#', 
				'sub' => array(
					131 => array('title' => 'Add Employer', 'url' => HTTPPATH.'/index.php?page=admin/employer_add'),
					132 => array('title' => 'Employers List', 'url' => HTTPPATH.'/index.php?page=admin/employer_list'),
				)
			),
			14 => array(
				'title' => 'Vendors', 
				'url' => '#', 
				'sub' => array(
					141 => array('title' => 'Add Vendor', 'url' => HTTPPATH.'/index.php?page=admin/vendor_add'),
					142 => array('title' => 'List Vendors', 'url' => HTTPPATH.'/index.php?page=admin/vendor_list'),
				)
			)
        )
    ),
    2 => array(
        'title' => 'Employer', 
        'url' => HTTPPATH.'/index.php?page=employer/index',
        'sub' => array(
            21 => array('title' => 'Join', 'url' => HTTPPATH.'/index.php?page=employer/join'),
        )
    ),
    3 => array(
        'title' => 'Employee', 
        'url' => HTTPPATH.'/index.php?page=employee/index',
        'sub' => array(
            31 => array('title' => 'Join', 'url' => HTTPPATH.'/index.php?page=employee/join'),
			32 => array('title' => 'Login', 'url' => HTTPPATH.'/index.php?page=employee/login'),
        )
    ),
    4 => array(
        'title' => 'Vendor', 
        'url' => HTTPPATH.'/index.php?page=vendor/index',
        'sub' => array(
            41 => array('title' => 'Join', 'url' => HTTPPATH.'/index.php?page=vendor/join'),
        )
    )
);
?>