<?php

class Mongo_Common{

	//更新类型
	const UPDATE_TYPE_DEF = 0;	// 替换整个文档，更新时最好以“_id”为查询条件。不能同其它更新同时使用。不建议使用
	const UPDATE_TYPE_SET = 1;	// 全部数据类型都支持
	const UPDATE_TYPE_INC = 2;	// 对一个字段增加一个值，如field = field+value
	const UPDATE_TYPE_PUSH = 3;	// 把value追加到field里面去，field一定要是数组类型才行，如果field不存在，会新增一个数组类型加进去
	const UPDATE_TYPE_PUSHALL = 4; // 一次可以追加多个值到一个数组字段内
	const UPDATE_TYPE_UNSET = 5;	// 删除字段 { $unset : { field : 1} }
	const UPDATE_TYPE_ADDTOSET = 6;	// 增加一个值到数组内，而且只有当这个值不在数组内才增加
	const UPDATE_TYPE_POP = 7;		// 删除数组内的一个值 用法：删除最后一个值：{ $pop : { field : 1 } }删除第一个值：{ $pop : { field : -1 } }
	const UPDATE_TYPE_PULL = 8;		// 从数组field内删除一个等于value值
	const UPDATE_TYPE_PULLALL = 9;	// 一次删除数组内的多个值  用法：{ $pullAll : { field : value_array } }

	//更新类型对应的操作符
	protected $UPDATE_TYPE = array(
		self::UPDATE_TYPE_SET		=> '$set',
		self::UPDATE_TYPE_INC		=> '$inc',
		self::UPDATE_TYPE_PUSH		=> '$push',
		self::UPDATE_TYPE_PUSHALL	=> '$pushAll',
		self::UPDATE_TYPE_UNSET		=> '$unset',
		self::UPDATE_TYPE_ADDTOSET	=> '$addToSet',
		self::UPDATE_TYPE_POP		=> '$pop',
		self::UPDATE_TYPE_PULL		=> '$pull',
		self::UPDATE_TYPE_PULLALL	=> '$pullAll',
	);

	//操作类型
	const OP_INSERT = 0;
	const OP_DELETE = 1;
	const OP_UPDATE = 2;
	const OP_FIND = 3;
	const OP_FINDONE = 4;
	const OP_COUNT = 5;

	//字段类型
	const TYPE_ID		= "id";
	const TYPE_INT		= "int";
	const TYPE_FLOAT	= "float";
	const TYPE_TEXT		= "text";
	const TYPE_BIN		= "bin";
	const TYPE_ARRAY	= "array";
	const TYPE_DOCUMENT = "documet";

	//参数作用
	const PARAM_USED_DEFAULT = 0;
	const PARAM_USED_INSERT = 1;
	const PARAM_USED_UPDATE = 2;
	const PARAM_USED_QUERY  = 3;
}
