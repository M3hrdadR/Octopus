<?php

class OwlRequestMother{
	public function __construct($data){
		if(is_array($data) and !empty($data))
			foreach($data as $key => $value)
				if(property_exists($this, $key))
					$this->{$key} = $value;
	}
	public function toarray(){
		return json_decode(json_encode($this), true);
	}
}