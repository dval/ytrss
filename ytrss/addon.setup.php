<?php
return [
    'author'         => 'Dylan Valentine',
    'author_url'     => 'https://github.com/dval/ytrss',
    'name'           => 'ytRSS',
    'description'    => 'Retrieve and parse YouTube RSS',
    'version'        => '1.0.0',
    'namespace'      => 'user\addons\ytrss',
    'plugin.typography'=>TRUE,
    'fieldtypes' => array(
        'ytrss' => array(
        'name' => 'ytrss',
        'compatibility' => 'text'
        )
    ),
    'settings_exist' => FALSE,
];