<?php

class Mongo_verifyParam
{
	private $_fields = array();
	private $_collection_name = "";
	
	public function __construct( $fields, $collectionName ){
		$this->_fields = $fields;

		$this->_collection_name = $collectionName;
	}

	private function initName( $fields ){
		$temp = array();
		foreach( $fields as $field => $info ){
			if( isset( $info['fields'] ) )
				$temp[$field] = $this->initName( $info['fields'] );
			else{
				$temp[] = $field;
			}
		}
		return $temp;
	}

	//字段是否为操作符
	private function isOperator( $keyword ){
		if(empty($keyword)) return false;


	}

	public function issetField( $field, $fields = array() ){
		if( empty($fields) ) $fields = $this->_fields;
		$temp = explode( ".", $field );
		$count = count( $temp );

		$parent = null;
		foreach( $temp as $ex ){
			if( $ex == '$' )
				continue;
			elseif( $parent == null ){

				if( !isset($fields[$ex]) ){
					return false;
				}
			}
			else{
				if( !isset( $fields[$parent]['fields'] ) ) return false;
				if( !$this->issetField( $ex, $fields[$parent]['fields'] ) ) return false;
			}
			$parent = $ex;
			
		}
		return true;
	}

	//校验Update数据
	public function verifyUpdate( $op, $data ){
		switch($op){
			case Mongo_Common::UPDATE_TYPE_SET:
				$this->verifyData( $data );
				break;
			case Mongo_Common::UPDATE_TYPE_INC:
				foreach( $data as $key => $val ){
					if( !is_numeric($val) ) throw Mongo_Exception('$inc must be numberic.');
					$data[$key] = intval($val);
				}
				break;
			case Mongo_Common::UPDATE_TYPE_PUSH:
			case Mongo_Common::UPDATE_TYPE_PULL:
				foreach( $data as $key => $val ){
					if( $this->getType( $key ) != "array" )
						error_log("[{$this->_collection_name}]$key is not array type.");
				}
				break;
			case Mongo_Common::UPDATE_TYPE_PULLALL:
			case Mongo_Common::UPDATE_TYPE_PUSHALL:
				foreach( $data as $key => $val ){
					if( $this->getType( $key ) != "array" )
						error_log("[{$this->_collection_name}]$key is not array type.");
				}
				break;
			case Mongo_Common::UPDATE_TYPE_UNSET:
				foreach( $data as $key => $val ){
					if( !is_numeric($val) ) throw Mongo_Exception('$unset must be numberic.');
					$data[$key] = intval($val);
				}
				break;
			case Mongo_Common::UPDATE_TYPE_ADDTOSET:
				error_log("\$addToSet : I do not know how to do.");
				break;
			case Mongo_Common::UPDATE_TYPE_POP:
				foreach( $data as $key => $val ){
					if( !is_numeric($val) ) throw Mongo_Exception('$unset must be numberic.');
					$data[$key] = intval($val);
					if( abs($data[$key]) != 1){
						error_log("\$pop : Can only delete the first or last.");
					}
				}
				break;
			case Mongo_Common::UPDATE_TYPE_DEF:
			default:
				$this->verifyData( $data );
				break;
		}

		return $data;
	}

	//以data的纬度进行校验
	public function verifyData( &$data ){
		foreach( $data as $key => $val ){
			if( !isset($this->_fields[$key]) ){
				$temp = explode('.', $key );
				if( count($temp) == 1 )
					error_log("[{$this->_collection_name}]Not found field : $key.");
				else
					continue;
			}
			$this->verify( $key, $data[$key] );
		}
	}

	//校验单个数据
	public function verify( $key, &$val, $used_type = Mongo_Common::PARAM_USED_DEFAULT ){
		if( !$this->issetField( $key ) ){
			error_log("[{$this->_collection_name}]Undeclared field : $key.");
			return;
		}
		$type = $this->getType( $key );
		switch( $type ){
			case Mongo_Common::TYPE_ID:
				if( !is_object( $val ) && is_string($val) ) $val = new MongoId($val);
				break;
			case Mongo_Common::TYPE_INT:
				if( is_object( $val ) || is_array($val) ){
					error_log( "[{$this->_collection_name}]Error data type, the $key is int type." );
					return ;
				}
				$val = intval($val);
				break;
			case Mongo_Common::TYPE_FLOAT:
				if( is_object( $val ) || is_array($val) ){
					error_log( "[{$this->_collection_name}]Error data type, the $key is float type." );
					return ;
				}
				$val = floatval( $val );
				break;
			case Mongo_Common::TYPE_TEXT:
				if( $val instanceof MongoId ){
					$val = $val->__toString();
				}
				elseif( is_object( $val ) || is_array($val) ){
					error_log( "[{$this->_collection_name}]Error data type, the $key is text type." );
					return ;
				}
				$val = strval( $val );
				break;
			case Mongo_Common::TYPE_BIN:
				if( !is_object( $val ) || !($val instanceof MongoBinData) ){
					error_log("[{$this->_collection_name}]Error data type, the $key is bin type.");
				}
				break;
			case Mongo_Common::TYPE_ARRAY:
				if( !is_array($val) && $used_type != Mongo_Common::PARAM_USED_QUERY ){
					error_log("[{$this->_collection_name}]$key is array type.");
				}
				break;
			case Mongo_Common::TYPE_DOCUMENT:
				if( !is_array($val) ) error_log("[{$this->_collection_name}]$key is document type.");
				break;
			default:
				break;
		}
	}

	public function verifyQuery( array &$data ){

		foreach( $data as $field => $val ){

			if( !$this->issetField($field) ){
				if( strpos($field, '$') === false && strpos($field, '.') === false ){
					error_log("[{$this->_collection_name}]invalid field : $field.");
                }else{
					$data[$field] = $this->verifyGrammar( $field, $val );
                }
				continue;
			}
			elseif( !is_array( $val ) ){
				$this->verify( $field, $data[$field], Mongo_Common::PARAM_USED_QUERY );
				continue;
			}
			$type = $this->getType( $field );
			foreach( $val as $op => $v ){

				$data[$field][$op] = $this-> verifyGrammar( $op, $v, $field );
			}

		}
	}

	/**
	* 校验查询条件语法
	**/
	//http://hi.baidu.com/farmerluo/item/c25e8deb482f470c65db00b2
	public function verifyGrammar( $op, $val, $field=null ){
		switch( $op ){
			case '$gt':
			case '$lt':
			case '$gte':
			case '$lte':
				$type = $this->getType( $field );
				if( !in_array( $type, array( "int", "float" ) ) )
					throw new Mongo_Exception("Operator($op), field must be numberic."); 
				$this->verify( $field, $val );
				break;
			case '$ne':
				$this->verify( $field, $val );
				break;
			case '$all':
			case '$in':
			case '$nin':
				if( !is_array( $val ) ){
					throw new Mongo_Exception("Operator($op), value must be array.");
				}
				foreach( $val as $index => $atom ){
					$this->verify( $field, $val[$index] );
				}
				break;
			case '$near':
				if( !is_array( $val ) )
					throw new Mongo_Exception("Operator($op), field must be array.");

				break;
			case '$mod':
				$type = $this->getType( $field );
				if( !in_array( $type, array( "int" ) ) )
					throw new Mongo_Exception("Operator($op), field must be numberic.");
				foreach( $val as $index => $atom ){
					$this->verify( $field, $val[$index] );
				}
				break;
			case '$size':
				$type = $this->getType( $field );
				/*if( !in_array( $type, array( "array" ) ) )
					throw new Mongo_Exception("Operator($op), field must be array.");*/
				$val = intval( $val );
				break;
			case '$exists':
				$val = $val ? true : false;
				break;
			case '$type'://http://docs.mongodb.org/manual/reference/glossary/#term-bson
				//先不做处理，暂时应该用不到
				break;
			case 'elemMatch':
				$type = $this->getType( $field );
				if( !in_array( $type, array( "array" ) ) )
					throw new Mongo_Exception("Operator($op), field must be array.");
				//如果对象有一个元素是数组，那么$elemMatch可以匹配内数组内的元素
				if( !is_array( $val ) )
					throw new Mongo_Exception("Operator($op), value must be array.");
				break;
			case '$and':
			case '$or':
			case '$nor':
				if( !is_array( $val ) ){
					throw new Mongo_Exception("Operator($op), value must be array.");
				}
				foreach( $val as $index => $atom ){
					$this->verifyQuery( $atom );
					$val[$index] = $atom;
				}
				break;
			case '$query':
				if( !is_array( $val ) ){
					throw new Mongo_Exception("Operator($op), value must be array.");
				}
				$this->verifyQuery($val);
				break;
			case '$where':
			default:
				break;
		}
		return $val;
	}

	public function getType( $field ){

		$temp = explode( ".", $field );
		$count = count( $temp );

		$old = $this->_fields;
		$parent = $this->_fields;

		$ex = "";
		for( $i = 0; $i < $count; $i++ ){
			$ex = $temp[$i];
			if( $ex == '$' ){
				continue;
			}else{
				$old = $parent;
				$parent = isset($parent[$ex]['fields']) ? $parent[$ex]['fields'] : array();
			}
		}
		return $old[$ex]['type'];
	}

}
