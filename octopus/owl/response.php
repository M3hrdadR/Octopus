<?php

class ERRMother{
	const NONE								= "ERR_NONE";
	const INTERNAL							= "ERR_INTERNAL";
}

class OwlResponseMother{
	public $error = ERRMother::NONE;
	public function __construct($data = 0){
		if(is_array($data)){
			foreach($data as $key => $value)
				if(property_exists($this, $key))
					$this->{$key} = $value;				
		}
		elseif(is_string($data))
			$this->error = $data;
	}
	public function respond(){
		echo json_encode($this);
	}
}