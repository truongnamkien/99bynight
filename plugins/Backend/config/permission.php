<?php

return [
    'permissionExcept' => [
        'Profile' => true,
    ],
    'moduleList' => [
        [
            'label' => 'Admin Management',
            'subModules' => [
                'AdminRoles' => [
                    'label' => 'Admin Roles',
                    'iconClass' => 'fas fa-user-friends text-orange',
                    'controller' => 'AdminRoles',
                ],
                'AdminUsers' => [
                    'label' => 'Admin',
                    'iconClass' => 'ni ni-single-02 text-primary',
                    'controller' => 'AdminUsers',
                ],
            ],
        ],
        [
            'label' => 'Content Management',
            'subModules' => [
                'Banners' => [
                    'label' => 'Banners',
                    'iconClass' => 'fas fa-images text-green',
                    'controller' => 'Banners',
                ],
                'Pages' => [
                    'label' => 'Pages',
                    'iconClass' => 'fas fa-pen-nib text-primary',
                    'controller' => 'Pages',
                ],
                'Configs' => [
                    'label' => 'Configs',
                    'iconClass' => 'fas fa-cog text-orange',
                    'controller' => 'Configs',
                ],
            ],
        ],
        [
            'label' => 'Blog Management',
            'subModules' => [
                'BlogCategories' => [
                    'label' => 'Blog Categories',
                    'iconClass' => 'fas fa-atlas text-orange',
                    'controller' => 'BlogCategories',
                ],
                'Blogs' => [
                    'label' => 'Blogs',
                    'iconClass' => 'fas fa-newspaper text-primary',
                    'controller' => 'Blogs',
                ],
            ],
        ],
        [
            'label' => 'Menu Management',
            'subModules' => [
                'ProductCategories' => [
                    'label' => 'Product Categories',
                    'iconClass' => 'fas fa-utensils text-orange',
                    'controller' => 'ProductCategories',
                ],
                'Products' => [
                    'label' => 'Products',
                    'iconClass' => 'fas fa-burger text-primary',
                    'controller' => 'Products',
                ],
            ],
        ],
    ],
];
