<?php

Class Controller 
{ 
	public function post( $post ){
		if ( !EMPTY($_POST[$post]) ){
			return $_POST[$post];
		}
	}
	
	public function get( $get ) {
		if ( !EMPTY ($get) ){
			return $_GET[$get];
		}
	}	

	public function loadModel( $class ){  
		if( file_exists( MODEL_DIR . $class . EXT ) )
		{
			include_once( MODEL_DIR . $class . EXT );
			
			if( class_exists( $class ) ) {
				return new $class;
			}
		}
	}

	public function loadController( $class){
		if( file_exists( CONTROLLER_DIR . $class . EXT ) )
		{
			include_once( CONTROLLER_DIR . $class . EXT );
			if( class_exists( $class ) ) {
				return new $class;
			}
		}
	}  
	
	public function loadView( $view_file , $data = array() ){ 
		ob_start();
		include( VIEW_DIR . $view_file . EXT );
		ob_end_flush();
	}
	
	public function loadLibrary ( $lib_name, $lib_directory ){
		$library = load_class( $lib_name, $lib_directory );
		return $library;
	}

	public function redirect($url)
	{ 
		header("Location:" . BASE_URL.'index.php?'.$url);
	}
}
?>