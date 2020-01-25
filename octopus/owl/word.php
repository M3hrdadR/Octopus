<?php

class OwlWordMother{
	public $id;
	const id_type = "INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
	
	private function connect(){
		$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if($con->connect_error)
			return false;
		if(!$con->set_charset("utf8"))
			return false;
		return $con;
	}
		
	public function __construct($data = NULL){
		if(!empty($data)){
			if(is_array($data)){
				foreach($data as $key => $value)
					if(property_exists($this, $key))
						$this->{$key} = $value;				
			}
			else
				$this->select($data);
		}
	}
	
	public function rec_table(){
		$con = self::connect();
		if(!$con)
			return false;

		$table = strtolower(get_class($this))."s";
		
		if($con->query("DROP TABLE IF EXISTS `$table`") !== TRUE){
			$con->close();
			return false;
		}

		$properties = get_object_vars($this);
		$cols = [];

		foreach($properties as $col => $val){
			$type = constant(get_class($this)."::".$col."_type");
			if($type == "JSONARRAY" or $type == "JSONOBJECT")
				$type = "TEXT";
			$cols[] = "`$col` ".$type." NOT NULL";
		}

		$cols = implode(",", $cols);

		if($con->query("CREATE TABLE `$table` ($cols) CHARACTER SET = utf8") !== TRUE){
			$con->close();
			return false;
		}

		$con->close();
		return true;
	}

	public function insert(&$id = null){
		$con = self::connect();
		if(!$con)
			return false;
		
		$properties = get_object_vars($this);
		$cols = [];
		$vals = [];
		foreach($properties as $col => $val){
			$cols[] = $col;
			
			$type = constant(get_class($this)."::".$col."_type");
			switch($type){
				case "JSONARRAY":
					if(is_array($val))
						$val = json_encode($val);
					else
						$val = json_encode([]);
					break;
				case "JSONOBJECT":
					if(is_object($val))
						$val = json_encode($val);
					else
						$val = json_encode((object)[]);
					break;
				case "DATETIME":
					$val = date('Y-m-d H:i:s', $val);
					break;
				default:
					$val = strval($val);
			}

			$vals[] = $con->real_escape_string($val);
		}
		$cols = "`".implode("`,`", $cols)."`";
		$vals = "'".implode("','", $vals)."'";
		$table = strtolower(get_class($this))."s";

		if($con->query("INSERT INTO `$table` ($cols) VALUES ($vals)") !== TRUE){
			$con->close();
			return false;
		}
		
		$id = $con->insert_id;
		$con->close();
		return true;

	}
	
	public function update(){
		$con = self::connect();
		if(!$con)
			return false;
		
		$properties = get_object_vars($this);
		$updates = [];

		foreach($properties as $col => $val){
			$type = constant(get_class($this)."::".$col."_type");
			switch($type){
				case "JSONARRAY":
					if(is_array($val))
						$val = json_encode($val);
					else
						$val = json_encode([]);
					break;
				case "JSONOBJECT":
					if(is_object($val))
						$val = json_encode($val);
					else
						$val = json_encode((object)[]);
					break;
				case "DATETIME":
					$val = date('Y-m-d H:i:s', $val);
					break;
				default:
					$val = strval($val);
			}

			$updates[] = "`$col` = '".$con->real_escape_string($val)."'";
		}
		$updates = implode(",", $updates);
		$table = strtolower(get_class($this))."s";

		if($con->query("UPDATE `$table` SET $updates WHERE `id` = '{$this->id}'") !== TRUE){
			$con->close();
			return false;
		}

		$con->close();
		return true;
	}

	public function delete(){
		$con = self::connect();
		if(!$con)
			return false;
		
		$table = strtolower(get_class($this))."s";

		if($con->query("DELETE FROM `$table` WHERE `id` = '{$this->id}'") !== TRUE){
			$con->close();
			return false;
		}

		$con->close();
		return true;
	}
	
	private function select($id){
		if(!is_int($id) and !ctype_digit($id))
			return [];

		$con = self::connect();
		if(!$con)
			return false;
				
		$table = strtolower(get_class($this))."s";

		$result = $con->query("SELECT * FROM `$table` WHERE `id` = '".$con->real_escape_string($id)."'");
		$con->close();
		
		if($data = $result->fetch_assoc()){
			foreach($data as $key => $val)
				if(property_exists($this, $key)){
					$type = constant(get_class($this)."::".$key."_type");
					switch($type){
						case "JSONARRAY":
							$val = json_decode($val);
							if(json_last_error() != JSON_ERROR_NONE)
								$val = [];
							break;
						case "JSONOBJECT":
							$val = json_decode($val);
							if(json_last_error() != JSON_ERROR_NONE)
								$val = (object)[];
							break;
						case "DATETIME":
							$val = strtotime($val);
							break;
					}

					$this->{$key} = $val;
				}
			return true;
		}
		else
			return false;
	}
	
	public function search($limit = 0, $offset = 0){
		if(!is_int($limit) and !ctype_digit($limit))
			return [];

		if(!is_int($offset) and !ctype_digit($offset))
			return [];
		
		$con = self::connect();
		if(!$con)
			return [];

		$properties = get_object_vars($this);
		$conditions = ["1 = 1"];
		
		foreach($properties as $col => $val)
			if(!is_null($val)){
				$type = constant(get_class($this)."::".$col."_type");
				switch($type){
					case "JSONARRAY":
						if(is_array($val))
							$val = json_encode($val);
						else
							$val = json_encode([]);
						break;
					case "JSONOBJECT":
						if(is_object($val))
							$val = json_encode($val);
						else
							$val = json_encode((object)[]);
						break;
					case "DATETIME":
						$val = date('Y-m-d H:i:s', $val);
						break;
					default:
						$val = strval($val);
				}

				$conditions[] = "`$col` = '".$con->real_escape_string($val)."'";
			}

		$conditions = implode(" AND ", $conditions);
		$table = strtolower(get_class($this))."s";
		
		$limit_statement = "";
		if($limit)
			$limit_statement = "LIMIT $limit OFFSET $offset";

		$result = $con->query("SELECT * FROM `$table` WHERE $conditions $limit_statement");
		$con->close();

		$this_class = get_class($this);
		$items = [];
		if($result){
			while($data = $result->fetch_assoc()){
				$item = new $this_class();
				foreach($data as $key => $val)
					if(property_exists($item, $key)){
						$type = constant(get_class($this)."::".$key."_type");
						switch($type){
							case "JSONARRAY":
								$val = json_decode($val);
								if(json_last_error() != JSON_ERROR_NONE)
									$val = [];
								break;
							case "JSONOBJECT":
								$val = json_decode($val);
								if(json_last_error() != JSON_ERROR_NONE)
									$val = (object)[];
								break;
							case "DATETIME":
								$val = strtotime($val);
								break;
						}

						$item->{$key} = $val;
					}
				$items[] = $item;
			}			
		}
		return $items;
	}
}
