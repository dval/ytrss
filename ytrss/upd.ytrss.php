<?php
/**
 * Since we don't really do anything with EE internals 
 * or extend the functionality in a signifigant way, 
 * we can use a rather sparse installer.
 */ 
use ExpressionEngine\Service\Addon\Installer;

class Query_upd extends Installer
{
    public function __construct()
    {
        parent::__construct();
    }

}