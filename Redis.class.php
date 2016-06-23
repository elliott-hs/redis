<?php 
namespace Common\Model;
class Redis{
	
	public function __construct($options=array()) {
		if(empty($options)) {
			$options = array (
				'host'	=> C('REDIS_HOST') ? C('REDIS_HOST') : '127.0.0.1',
				'port'	=> C('REDIS_PORT') ? C('REDIS_PORT') : 6379,
				'auth'	=> C('REDIS_AUTH') ? C('REDIS_AUTH') : '',
			);
		}
		$this->options =  $options;    
		$func = isset($options['persistent'])? 'pconnect' : 'connect';
		$this->redis  = new \Redis;
		$this->redis->connect($this->options['host'], $this->options['port']);
		if($this->options['auth'])  $this->redis->auth($this->options['auth']);
	}

	public function exists($key){
		return $this->redis->exists($key);
	}

	/**
	 * 队列—左存储
	 * @param string $name  队列名称
	 * @param srting array $member  队列元素
	 * @return  boolean 
	 */
	public function lpush($name,$data){
		if(!is_array($data)) $data =array($data);
		$member = array_merge(array($name),$data);
		// return call_user_func_array(array($this->redis,"lpush"),$member);
		//临时使用
		foreach ($data as  $value) {
			# code...
			$i = $this->redis->lpush($name,$value);
			if($i) ++$j;

		}
		return $j;
	}

	/**
	 * 队列—右存储
	 * @param string $name  队列名称
	 * @param srting array $member  队列元素
	 * @return  boolean 
	 */
	public function rpush($name,$data){
		$member = array_merge(array($name),$data);
		return call_user_func_array(array($this->redis,"rpush"),$member);
	}

	/**
	 * 队列—左出队
	 * @param string $name  队列名称
	 * @return  boolean 
	 */
	public function lpop($name){
		return $this->redis->lpop($name);
	}

	/**
	 * 队列—右出队
	 * @param string $name  队列名称
	 * @return  boolean 
	 */
	public function rpop($name){
		return $this->redis->rpop($name);
	}

	/**
	 * 检索全部
	 * @return  array
	 */
	public function getAll(){
		return  $this->redis->keys("*");
	}

	/**
	 * 存储集合
	 * @param string $name  集合名称
	 * @param srting array $member  集合元素
	 * @return  boolean
	 */
	public function sadd($name,$data){
		$member = array_merge(array($name),$data);
		return call_user_func_array(array($this->redis,"sadd"),$member);
	}
	/**
	 * 检索集合
	 * @param string $name  集合名称
	 * @param string $member  集合元素
	 * @return   boolean 
	 */
	public function sismember($name,$member){
		if(!$member)  return false;
		return $this->redis->sIsMember($name,$member);
	}
	/**
	 * 标记一个事务块开始
	 * @param string $name  集合名称
	 * @param string $member  集合元素
	 * @return   boolean 
	 */
	public function multi(){
		$this->redis->multi();
	}
	/**
	 * 执行所有 MULTI 之后发的命令
	 * @param string $name  集合名称
	 * @param string $member  集合元素
	 * @return   boolean 
	 */
	public function exec(){
		$this->redis->exec();
	}
	/**
	 * 丢弃所有 MULTI 之后发的命令
	 * @param string $name  集合名称
	 * @param string $member  集合元素
	 * @return   boolean 
	 */
	public function discard(){
		$this->redis->discard();
	}

}
?>