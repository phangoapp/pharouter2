<?php

use PhangoApp\PhaRouter2\Router;

function GenerateRoutesConsole() 
{

    //Get modules
    foreach(Router::$modules as $module)
    {
        
        $path_module=__DIR__.'/vendor/'.$module.'/';
        
        $arr_dir=scandir($path_module);
        
    }

}

?>
