<?php

class Mongo_Set
{
	private $_cursor = null;

	public function __construct( MongoCursor $c ){
		if( empty($c) ) throw new Mongo_Exception("Must be effective MongoCursor object.");

		$this->_cursor = $c;
	}

	public function getCursor(){
		return $this->_cursor;
	}

	public function count(){
		return $this->_cursor->count();
	}

	public function skip( $skip ){
		$this->_cursor = $this->_cursor->skip( $skip );
		return $this;
	}

	public function limit( $limit ){
		$this->_cursor = $this->_cursor->limit( $limit );
		return $this;
	}

	public function sort( array $sort ){
		$this->_cursor = $this->_cursor->sort( $sort );
		return $this;
	}

	public function fields( array $fields ){
		$this->_cursor = $this->_cursor->fields( $fields );
		return $this;
	}

    public function timeout( $ms = 30000 ){
        $this->_cursor = $this->_cursor->timeout($ms);
        return $this;
    }

	public function __toArray( array $as = array() ){
		$result = array();
		foreach( $this->_cursor as $key=>$val ){
			if(!is_numeric($key) || $key>100000000){
				$val['_id'] = $key;
			}

			foreach( $as as $k => $v )
				if( isset($val[$k]) ){
					$val[$v] = $val[$k];
					unset( $val[$k] );
				}
			$result[] = $val;
		}

		return $result;
	}

	public function __toString(){
		return json_encode( $this->__toArray() );
	}
}
