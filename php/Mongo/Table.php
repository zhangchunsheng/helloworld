<?php

class Mongo_Table extends Mongo_Common
{
	private static $_adapter = null;
    private static $_serialize = null;
	private static $_m = null;
	private static $_db = null;
	private $_collection = null;

	private $_op = null;
	private $_string = null;

	//是否为操作的开始
	private $_isBegin = true;
	//是否校验列类型
	private $_isVerify = true;
	private $_verifyParam = null;

	private $_safe_options = array();
	//查询条件
	private $_where = array();
	//插入时的临时数据
	private $_insert_data = array();
	//更新时的临时数据
	private $_update_data = array();
	//更新用到的参数
	private $_update_options = array();

	/**
	* 设置默认配置
	* @param $connect_conf  连接配置
	* @param $database_conf 数据表配置
	* @return void
	**/
	static function setDefaultConfig( $connect_conf, $database_conf = array() ){
		if( empty($connect_conf) )
			throw new Mongo_Exception("Invalid adapter.");

        if(isset($connect_conf['database_conf']) && !empty($connect_conf['database_conf'])){
            if(is_array($connect_conf['database_conf'])){
                $database_conf = $connect_conf['database_conf'];
            }elseif(is_file($connect_conf['database_conf'])){
                $database_conf = require($connect_conf['database_conf']);
            }
            unset($connect_conf['database_conf']);
        }

		$serialize = md5(serialize($connect_conf));
        self::$_serialize = $serialize;
        if(isset(self::$_adapter[$serialize])){
            return ;
        }

		self::$_adapter[$serialize] = array(
			"connect" => $connect_conf,
			"database" => $database_conf,
			"serialize" => $serialize,
		);

		$dbList = isset(self::$_adapter["db"]) ? self::$_adapter["db"] : array();
		$db = isset($dbList[$serialize]) ? $dbList[$serialize] : null;
		if( empty($db) ||
				!is_object($db) ||
				!($db instanceof MongoDB) ){
			$config = self::$_adapter[$serialize]["connect"];

			$conn = "mongodb://";
			$conn .= join( $config['server'], ',' );
            $options = array(
                    'replicaSet' => isset($config['repl_set']) ? $config['repl_set'] : '',
                    'connectTimeoutMS' => isset($config['connect_timeout']) ? $config['connect_timeout'] : 3000,
                    'socketTimeoutMS' => isset($config['socket_timeout']) ? $config['socket_timeout'] : 10000,
                    );
            $m = new Mongo( $conn, $options );

            $db = $m->selectDB( $config["db_name"] );
            $username = isset($config['username']) ? $config['username'] : '';
            $password = isset($config['password']) ? $config['password'] : '';
            if(!empty($username) && !empty($password)){
                $res = @$db->authenticate( $config["username"], $config["password"] );
            }


            if(isset($config['slave']) && $config['slave']){
                $slave = $config['slave'] ? true : false;
                $db->setSlaveOkay($slave);
            }

			$dbList[$serialize] = $db;
		}

		self::$_adapter[$serialize]['db'] = $db;
	}

	public function __construct( $collectionName, $verify = true ){
		if( empty(self::$_adapter[self::$_serialize]['db']) )
			throw new Mongo_Exception("Ps use Mongo_Table::setDefaultConfig first.");
        $adapter = self::$_adapter[self::$_serialize];
        self::$_db = $adapter['db'];

		if(!empty($adapter["database"]) && isset($adapter['database'][$collectionName])){
            $collection = $this->_genCollectionName($collectionName);
            $this->_collection = self::$_db->selectCollection( $collection );
			if( $this->_isVerify = $verify ){
                $config = $adapter['database'][$collectionName]['fields'];
				$this->_verifyParam = new Mongo_VerifyParam( $config, $collection );
            }
		}else{
			$this->_collection = self::$_db->selectCollection( $collectionName );
			$this->_isVerify = false;
		}
		$this->setSafeOption();
	}

    //生成表名
    private function _genCollectionName($name){
        $collection = $name;
        //如果配置分表
        if(!isset(self::$_adapter['database'][$collection])){
            return $name;
        }
        $config = self::$_adapter['database'][$collection];
        $collection = $config['collection'];
        $config = isset($config['separation']) ? $config['separation'] : array(); 
        if(empty($config) || !isset($config['rule']) || 
                !isset($config['format']) || empty($config['format']) ||
                (isset($config['mode']) && $config['mode'] == 2) //如果是定期分表模式，直接返回。缺省为动态分表
                ){
            return $collection;
        }elseif(isset($config['rule'])){
            $rule = $config['rule'];
            $name = isset($config['format']) ? $config['format'] : $collection;
            
            $now = time();
            $timeInfo = getdate($now);
            $val = intval(isset($rule['val']) ? $rule['val'] : 1);
            $val = $val < 1 ? 1 : $val;

            $type = isset($rule['type']) ? $rule['type'] : '';
            switch($type){
                case 'Y':
                    $temp = intval($timeInfo['year']);
                    $mod = $temp % $val;
                    $temp -= $mod;

                    $t = mktime(0, 0, 0, 1, 1, $temp);
                    break;
                default://默认按月分表
                    $type = 'M';
                case 'M':
                    $temp = intval($timeInfo['mon']);
                    $mod = $temp % $val;
                    $temp -= $mod;

                    $t = mktime(0, 0, 0, $temp, 1, $timeInfo['year']);
                    break;
                case 'D':
                    $temp = intval($timeInfo['yday']);
                    $mod = $temp % $val;
                    $temp = $now - ($mod * 24*60*60);
                    
                    $t = strtotime(date('Ymd', $temp));
                    break;
                case 'W':
                    $temp = intval(date('W',$now));
                    $mod = $temp % $val;
                    $wday = intval($timeInfo['wday']);
                    $temp = $now - ($mod * 7*24*60*60) - ($wday-1) * 24*60*60;
                    
                    $t = strtotime(date('Ymd', $temp));
                    break;
            }

            $name = date($name, $t);
            $name = str_replace('%1', $collection, $name);
            $name = str_replace('%2', $type, $name);
            $name = str_replace('%3', $val, $name);

            return $name;
        }
        return $collection;
    }

	private function _begin(){
		if( $this->_isBegin ){
			$this->_isBegin = false;
			$this->_cleanCondition();
		}
	}

	private function _end(){
		$this->_isBegin = true;
		$this->_op = null;
	}

	private function _cleanCondition(){
		$this->_where = array();
		$this->_insert_data = array();
		foreach( $this->UPDATE_TYPE as $key => $val ){
			$this->_update_data[$val] = array();
		}

		$this->_op = null;
		$this->setUpdateOptions();
	}

	/**
	* 返回自增ID
	**/
	public function getAutoIncrementId(){
		$command = array(
			'findandmodify' => 'ids',
			'update' => array( '$inc' => array( 'value' => 1 ) ),
			'query' => array( 'name' => $this->_collection->getName() ),
			'new' => true,
			'upsert' => true,
			'safe' => true
		);
		$res = self::$_db->command( $command );

		return $res['value']['value'];
	}


	public function __getDb(){
		return self::$_db;
	}

	/**
	* @func setSafeOption
	* @param $w 写入关注
		w=-1 不等待，不做异常检查
		w=0 不等待，只返回网络错误
		w=1 检查本机，并检查网络错误
		w>1 检查w个server，并返回网络错误
	* @param $fsync 默认TRUE，忽略$w=0。如果FALSE，强制写入磁盘后返回成功
	* @param $timeout 毫秒，等待数据库响应时间
	**/
	public function setSafeOption( $fsync = true, $w = 1, $timeout = 2000 ){
		$this->_safe_options = array(
				'w' => $w,
				'fsync' => $fsync,
				'timeout' => $timeout,
			);

		return $this;
	}

	/**
	* 设置更新条件
	* @param $where
	* @return Mongo_Table
	**/
	public function where( array $where ){
		$this->_begin();
		if( !empty($where) && is_array($where) ) {
			if( $this->_isVerify ) $this->_verifyParam->verifyQuery( $where );
			$this->_where = array_merge( $this->_where, $where );
		}

		return $this;
	}

	public function setJavaScript( $js ){
		$this->_begin();
		//TO DO..
	}

	/**
	* @fun set  for update
	* @param $data
	* @param $type
	* @return Mongo_Table
	**/
	public function setUpdateData( array $data, $type = self::UPDATE_TYPE_SET ){
		$this->_begin();
		if( !is_array( $data ) ) throw new Mongo_Exception("Data must be array.");
		if( empty( $data ) ) return $this;

		if( $this->_isVerify )
			$data = $this->_verifyParam->verifyUpdate( $type, $data );
		$this->_update_data[ $this->UPDATE_TYPE[$type] ] = array_merge( $this->_update_data[ $this->UPDATE_TYPE[$type] ], $data );
		return $this;

	}

	/**
	* @fun set  for update
	* @param $field
	* #param $value
	* @param $type
	* @return Mongo_Table
	**/
	public function setUpdateValue( $field, $value, $type = self::UPDATE_TYPE_SET ){
		return $this->setUpdateData( array($field=>$value), $type );
	}
	/**
	* @fun setUpdateOptions
	* @Param $multiple 是否更新所有条件满足的数据，TRUE为更新所有数据，FALSE只更新找到的第一条
	* @param $upsert 如果未找到对应数据，是否新插入一条数据，TRUE为新插入一条数据
	**/
	public function setUpdateOptions( $multiple=false, $upsert=false ){
		$this->_begin();
		$this->_update_options = array('multiple'=>$multiple, 'upsert'=>$upsert);

		return $this;
	}

	/**
	* @fun update
	**/
	public function update(){
		$this->_end();
		if( empty($this->_where) || !is_array($this->_where) )
			throw new Mongo_Exception("Query conditions can not be empty.");

		foreach( $this->UPDATE_TYPE as $key => $val ){
			if( empty($this->_update_data[ $val ]) ) unset( $this->_update_data[ $val ] );
		}


		if( empty($this->_update_data) ){
			throw new Mongo_Exception("Update data can not be empty.");
		}

		$options = array_merge($this->_update_options, $this->_safe_options);
		try{
			$res = $this->_collection->update( $this->_where, $this->_update_data, $options );
		}
		catch( Exception $e ){
			throw $e;
		}
		$this->_op = self::OP_UPDATE;
		return $res;
	}


	/**
	* @fun findOne
	* @param $fields 需要查询的fields
	**/
	public function findOne( $fields = array() ){
		$this->_end();
		if( !is_array($this->_where) )
			throw new Mongo_Exception("Query conditions can not be empty.");
		$res = $this->_collection->findOne( $this->_where, $fields );

		$this->_op = self::OP_FINDONE;
		return $res;
	}

	/**
	* @fun find
	* @param $fields 需要查询的fields
	* @param $limit
	* @param $order
	**/
	public function find( $fields = array(), $limit = 0, $order = array(), $skip = NULL ){
		$this->_end();
		if( !is_array($this->_where) )
			throw new Mongo_Exception("Query conditions can not be empty.");

		//MongoCursor
		$cursor = $this->_collection->find($this->_where, $fields);

		$set = new Mongo_Set( $cursor );

		$limit = intval( $limit );
		if( $limit > 0 ){
			$set = $set->limit( $limit );
		}

		$skip = intval( $skip );
		if ( $skip > 0 ){
			$set = $set->skip( $skip );
		}

		if( !empty( $order ) ){
			$set = $set->sort( $order );
		}

		$this->_op = self::OP_FIND;
		return $set;
	}

	/**
	* @fun count
	* @param $limit
	* @param $skip
	**/
	public function count( $limit = 0, $skip = 0 ){
		$this->_end();

		if( !is_array($this->_where) )
			throw new Mongo_Exception("Query conditions can not be empty.");

		$count = $this->_collection->count( $this->_where, $limit, $skip );
		$this->_op = self::OP_COUNT;

		return $count;
	}

	/**
	* @func
	* @param $data array
	**/
	public function setInsertData( array $data ){
		$this->_begin();
		if( !is_array( $data ) ) throw new Mongo_Exception("Data must be array.");
		if( empty($data) ) return $this;

		$this->_insert_data = array_merge( $this->_insert_data, $data );
		return $this;
	}

	/**
	* @func setInsertValue
	* @param $field
	* @param $value
	**/
	public function setInsertValue( $field, $value ){
		$this->_begin();
		if( empty( $field ) ) return $this;
		if( $this->_isVerify ) $this->_verifyParam->verify( $field, $value );
		$this->_insert_data = array_merge( $this->_insert_data, array( $field => $value ) );

		return $this;
	}

	/**
	* @fun insert
	* @param $data
	**/
	public function insert(){
		$this->_end();
		$data = $this->_insert_data;
		if( empty( $data ) || !is_array( $data ) ) return false;
		if( $this->_isVerify ) {
			$this->_verifyParam->verifyData( $data );
		}
        if(!isset($data['_id'])){
            $data['_id'] = new MongoId();
        }elseif(!$data['_id'] instanceof MongoId){
            $data['_id'] = new MongoId($data['_id']);
        }

		$options = $this->_safe_options;
		try{
			$this->_op = self::OP_INSERT;
			$result = $this->_collection->insert( $data, $options );
		}
		catch( Exception $e ){
			error_log( $e->__toString() );
			return false;
		}

		if ($result)
		{
			$result = $data['_id'];
		}
		else
		{
			$result = FALSE;
		}

		$this->_op = self::OP_INSERT;
		return $result;
	}
	/**
	* @fun delete
	**/
	public function delete(){
		$this->_end();
		if( empty($this->_where) || !is_array($this->_where) )
			throw new Mongo_Exception("Query conditions can not be empty.");

		$res = $this->_collection->remove( $this->_where, array('justOne' => false) );
		$this->_op = self::OP_DELETE;
		return $res;
	}

	/**
	* @func group
	* @param $key group by $key
	* @param $initial 初始化值
	* @param $reduce a function
	*/
	public function group( array $key, array $initial, $reduce, $options = array()){
		if( empty($key) ) throw new Mongo_Exception("\$key must be not null");
		if( empty($reduce) ) throw new Mongo_Exception("\$reduce must be not null");
		return $this->_collection->group( $key, $initial, $reduce, $options );
	}

	public function __toString(){
		$str = "db.".$this->_collection->getName().".";
		$exportor = new Mongo_VarExportor();
		if( $this->_isBegin == true ){
			switch( $this->_op ){
				case self::OP_INSERT:
					$str .=
						"insert(".
							$exportor->setVar($this->_insert_data)->export(MONGO_EXPORT_JSON).
						")";
					break;
				case self::OP_DELETE:
					$str .=
						"remove(".
							$exportor->setVar($this->_where)->export(MONGO_EXPORT_JSON).
						")";
					break;
				case self::OP_UPDATE:
					$str .=
						"update(".
							$exportor->setVar($this->_where)->export(MONGO_EXPORT_JSON).",".
							$exportor->setVar($this->_update_data)->export(MONGO_EXPORT_JSON).",".
							($this->_update_options['upsert'] ? "true" : "false") .",".
							($this->_update_options['multiple'] ? "true" : "false") .
						")";
					break;
				case self::OP_FINDONE:
					$str .=
						"findOne(".
							$exportor->setVar($this->_where)->export(MONGO_EXPORT_JSON).
						")";
					break;
				case self::OP_FIND:
					$str .=
						"find(".
							$exportor->setVar($this->_where)->export(MONGO_EXPORT_JSON).
						").pretty()";
					break;
				case self::OP_COUNT:
					$str .=
						"count(".
							$exportor->setVar($this->_where)->export(MONGO_EXPORT_JSON).
						")";
					break;
				default:
					break;
			}
		}

		return $str;
	}

	public function getUpsertId( $result ){
		if( $this->_isBegin == true && $this->_op == self::OP_UPDATE ){
			if( !$result ) return false;
			elseif( isset( $result['upserted'] ) ) return $result['upserted'];
			else{
				$res = $this->findOne( array("_id"=>1) );
				if( $res ) return $res['_id']->__toString();
				return false;
			}
		}
	}


	/**
	* @func clean
	**/
	public function clean(){
		$res = $this->drop();

		if( $res['ok'] == 1 ){
			/*$nam = $this->_collection->getName();
			$config = Zend_Registry::get('db')->$name->toArray();
			$indexs = $config['indexs'];
			if( !empty( $indexs ) ){
				foreach( $indexs as $index ){
					if( empty( $index ) ) continue;
					$this->createIndex( $index );
				}
			}*/
		}
	}

	/**
	* @func createIndex
	* @param $keys
	* @param $unique 默认为非唯一索引
	* @param $dropDups 配合unique参数使用，默认不删除重复索引的数据
	**/
	public function createIndex( array $keys, $name = "", $unique = false, $dropDups = false ){
		if( empty($keys) || !is_array($keys) ) return false;
		$options['w'] = $this->_safe_options['w'];
		$options['timeout'] = $this->_safe_options['timeout'];
		if( $unique ){
			$options['unique'] = true;
			$options['dropDups'] = $dropDups;
		}
		if( !empty($name) ){
			$options['name'] = $name;
		}
		return $this->_collection->ensureIndex( $keys, $options );
	}

	/**
	* @func getIndex
	* @param array|string $key
	**/
	public function getIndex( $key = "" ){
		if( empty($key) ){
			$where = array(
				'ns' => $this->_collection->__toString(),
			);
			return $this->_collection->getIndexInfo();
		}
		elseif( is_string($key) ){
			$where = array(
				'ns' => $this->_collection->__toString(),
				'name' => $key,
			);
			$col = self::$_db->selectCollection("system.indexes");
			return $col->findOne($where);
		}
		elseif( is_array($key) ){
			$where = array(
				'ns' => $this->_collection->__toString(),
				'key' => $key,
			);
			$col = self::$_db->selectCollection("system.indexes");
			$res = $col->find($where);
			$res = new Mongo_Set( $res );
			return $res->__toArray();
		}
		return false;
	}
	/**
	* @func initCollection
	**/
	public function initCollection(){
		/*$name = $this->_collection->getName();
		$config = Zend_Registry::get('db')->$name->toArray();
		$indexs = $config['indexs'];
		if( !empty( $indexs ) ){
			foreach( $indexs as $index ){
				if( empty( $index ) ) continue;
				$this->createIndex( $index );
			}
		}*/
	}
	/**
	* @fun batchInsert
	* @param $data
	* @param $continueOnError
	**/
	public function batchInsert($data, $continueOnError = false){
		$this->_end();
		if( empty( $data ) || !is_array( $data ) ) return false;
		if( $this->_isVerify ) {
			foreach ($data as $k => $v)
			{
				if (empty($v) OR ! is_array($v))
				{
					unset($data[$k]);
				}
				$this->_verifyParam->verifyData( $v );
				$id = empty($v['_id']) ? "" : $v['_id'] ;
				if( is_object($id) ) $data[$k]['_id'] = $id;
				elseif( !empty($id) ) $data[$k]['_id'] = new MongoId($id);
			}
		}

		$options = $this->_safe_options;
		$options["continueOnError"] = $continueOnError;
		try{
			$result = $this->_collection->batchInsert($data, $options);
			$this->_op = self::OP_INSERT;
		}
		catch( Exception $e ){
			error_log( $e->__toString() );
			return false;
		}

		$this->_op = self::OP_INSERT;
		return $result;
	}

	public function lastError(){
		return self::$_db->lastError();
	}

    public function execute($code, $args=array()){
        return self::$_db->execute($code, $args);
    }
}
