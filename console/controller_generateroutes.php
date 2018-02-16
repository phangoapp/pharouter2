<?php

use PhangoApp\PhaRouter2\Router;

function GenerateRoutesConsole() 
{

    $arr_routes=[];
    $arr_order_routes=[];

    //Get modules
    foreach(Router::$apps as $app)
    {

        $path_app=Router::$root_path.'/'.$app.'/controllers';
        
        if(is_dir($path_app))
        {
            
            $arr_dir=scandir($path_app);
            
            foreach($arr_dir as $file)
            {

                if(preg_match('/^controller_.*\.php/', $file))
                {
                    $file=$path_app.'/'.$file;
                    if(is_file($file))
                    {
                        
                        $content=file_get_contents($file);
                        //@PhangoController /welcome/([0-9]+)/([a-zA-Z0-9_\-])+ PhangoApp\Welcome\Page
                        $pattern_file="|\@PhangoController (.*?)\s(.*?)\n|U";

                        if(preg_match_all ( $pattern_file, $content, $arr_match, PREG_SET_ORDER)) 
                        {

                            foreach($arr_match as $match)
                            {
                                
                                $arr_route=explode(' ', trim($match[1]));
                                
                                //$arr_routes[trim[$arr_route[0]]]=trim[$arr_route[1]];
                                
                                $arr_routes[trim($arr_route[0])]="Router::\$routes['".trim($arr_route[0])."']=['".trim($arr_route[1])."', '".$file."'];";
                                $arr_order_routes[]=trim($arr_route[0]);
                                
                            }

                        }
                    }
                    
                }
                
            }
            
        }
        
        
    }
    
    function resort($a,$b)
    {
        return strlen($b)-strlen($a);
    }

    uksort($arr_routes, 'resort');
    
    //$arr_final_routes=array_merge(array_flip($arr_order_routes), $arr_routes);
    
/*    $arr_final_routes=[];
    
    foreach($arr_order_routes as $order)
    {
        
        $arr_final_roures[$order]=$arr_routes[$order];
        
    }*/
    
    $config_file="<?php\n\nuse PhangoApp\PhaRouter2\Router;\n\n";
    $config_file.=implode("\n\n", $arr_routes)."\n\n";
    
    $path_file_app=Router::$base_path.'/settings/config_apps.php';

    if(!file_put_contents($path_file_app, $config_file))
    {
        
        echo "Error: i cannot create a new config file for routes";
        
    }
    else
    {
        
        echo "Created file with routes...\n";
        
                
    }

}

?>
