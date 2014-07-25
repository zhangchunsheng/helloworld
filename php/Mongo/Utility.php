<?php

class Mongo_Utility
{
	static public function expend( MongoDate $t_begin, MongoDate $t_end ){
		$e = $t_end->sec - $t_begin->sec;

		return ( ( $e * 100000  + $t_end->usec ) - $t_begin->usec );
	}

	static public function autoIncremented( $element ){
		$m = new Mongo_Table( "ids" );
		
		$command = array(
			'findandmodify' => 'ids',
			'update' => array( '$inc' => array( 'value' => 1 ) ),
			'query' => array( 'name' => $element ),
			'new' => true,
			'upsert' => true,
			'safe' => true
		);
		$res = $m->__getDb()->command( $command );

		return $res['value']['value'];
	}
}
