<?php

namespace PhangoApp\PhaRouter2;

class Router {
    
    
    static public $base_path='';

    static public $root_url='/';
    
    static public $apps=[];
    
    static public $app='';    
    
    static public $root_path='';
    
    static public $base_file='index.php';
    
    static public $routes=[];
    
    public function response($path_info) 
    {
        $func_route='';
        
        $args=[];
        
        foreach(Router::$routes as $k_route => $route)
        {
            
            $match=str_replace('/', '\/', $k_route);

            if(preg_match('/^'.$match.'$/', $path_info, $args))
            {
                
                list($func_route, $file_route)=$route;
                break;
                
            }
            
        }
        
        if($func_route!='')
        {
        
            if(is_file($file_route))
            {
                
                include($file_route);
                
                call_user_func_array($func_route , $args );
                
            }
        
        }
        else
        {
            
            $this->response404();
            
        }
    
    }
    
    public function response404()
	{
	
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
		
		/*$url404=$this->make_url($this->default_404['controller'], $this->default_404['method'], $this->default_404['values']);
		
		//Use views for this thing.
		
		if(!$this->response($url404, 0))
		{*/
		
		//use a view
		
        echo 'Error: page not found...';
			
		//}
		
		die;
		
		//$this->response($url404);
	}

    /*
	* Method for create urls for this route.
	*/
	
	static public function make_url($controller, $method='home', $values=array(), $get=array())
	{
	
		
	
		return Routes::$root_url.Routes::$base_file.'/'.Routes::$app.'/'.$controller.'/'.$method.'/'.implode('/', $values);
	
	}
	
	/**
	* Method for create urls for all routes in the site.
	*/
	
	static public function make_module_url($app, $controller, $method='home', $values=array(), $get=array())
	{
		$url_fancy=Routes::$root_url.Routes::$base_file.'/'.$app.'/'.$controller.'/'.$method.'/'.implode('/', $values);
		
		$url=Routes::add_get_parameters($url_fancy, $get);
	
		return $url;
	
	}
	
	/**
	* Method for create urls for all routes in differents sites.
	*/
	
	static public function make_direct_url($base_url, $app, $controller, $method='home', $values=array(), $get=array())
	{
	
		$url_fancy=$base_url.'/'.$app.'/'.$controller.'/'.$method.'/'.implode('/', $values);
		
		$url=Routes::add_get_parameters($url_fancy, $get);
		
		return $url;
	
	}
	
	/**
	* Method for create arbitrary urls. Is useful when use urls.php in your module.
	*/
	
	static public function make_simple_url($url_path, $values=array(), $get=array())
	{
	
        $url=Routes::$root_url.Routes::$base_file.'/'.$url_path.'/'.implode('/', $values);
        
        return Routes::add_get_parameters($url, $get);
	
	}
    
    /**
    * Alias of make_simple_url. Probably this method deprecated make_simple_url
    */
    
    static public function get_url($url_path, $values=array(), $get=array())
	{
        return Routes::make_simple_url($url_path, $values, $get);
    }
	
	/**
	* Function used for add get parameters to a well-formed url based on make_fancy_url, make_direct_url and others.
	*
	* @param string $url_fancy well-formed url
	* @param string $arr_data Hash with format key => value. The result is $_GET['key']=value
	*/

	static public function add_get_parameters($url_fancy, $arr_data)
	{

		$arr_get=array();
		
		$sep='';
		
		$get_final='';
		
		if(count($arr_data)>0)
		{

			foreach($arr_data as $key => $value)
			{

				$arr_get[]=$key.'/'.$value;

			}

			$get_final=implode('/', $arr_get);

			$sep='/get/';

			if(preg_match('/\/$/', $url_fancy))
			{

				$sep='get/';

			}
			
			
			if(preg_match('/\/get\//', $url_fancy))
			{

				$sep='/';

			}
			
		}

		return $url_fancy.$sep.$get_final;

	}
	
	/**
	* Method for make simple redirecs using header function.
	* @param string $url The url to redirect
	*/
	
	static public function redirect($url)
	{
	
		header('Location: '.$url);
	
		die;
	
	}

    
}
