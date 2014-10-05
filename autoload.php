<?php

spl_autoload_register(
    function($class) {
    	if ($class == 'Aprint')
    		require_once __DIR__."/{$class}.php";
});

