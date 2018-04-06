<?php 		
	class person {
		var $name;
		function __construct($persons_name) {		
			$this->name = $persons_name;		
		}		
 
		function set_name($new_name) {
		 	 $this->name = $new_name;
		}	
 
		function get_name() {		
		 	 return $this->name;		
		 }		
 
	}
	$stefan = new person("Stefan Mischook");
        echo "hello world\n";
	echo "Stefan's full name: ".$stefan->get_name()."\n";
?>
