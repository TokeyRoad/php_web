<?php

class redisx extends base{

    protected $redis = null;

    public function __construct($ip="127.0.0.1", $port = 6379, $auth = NULL) {
        if (null == $this->redis) {
            $this->redis = new Redis();
            if (!$this->redis->connect($ip, $port) || ($auth != NULL && !$this->redis->auth($auth)) ) {
                //
                $this->json_return(erron::ERROR_REDIS_ERROR, err_des::ERROR_REDIS_ERROR, null);
            }
        }
    }

    public function __destruct () {
        if (null != $this->redis) {
            $this->redis->close();
            $this->redis=null;
        }
    }

    /*
     * 获取某个排行榜中某个人的名次
     */
    public function zrevrank( $key, $member ) {
        if (null != $this->redis){
            return $this->redis->zRevRank( $key, $member);
        }
        return null;
    }

    public function zdelete( $key, $member ) {
        if (null != $this->redis){
            return $this->redis->zDelete($key, $member);
        }
        return null;
    }

    /*
     * 获取一个排行榜中的名次区间
     */
    public function zrevrange( $key, $start, $end, $withscore = null ) {
        if (null != $this->redis){
            return $this->redis->zRevRange( $key, $start,$end,$withscore);
        }
        return null;
    }
    /*
     * 删除排行榜的区间
     */
    public function zremrangebyrank( $key, $start, $end ) {
        if (null != $this->redis){
            return $this->redis->zRemRangeByRank( $key, $start, $end );
        }
        return null;
    }
    /*
     * 朝入一个数据到榜单中
     */
    public function zadd( $key, $score, $value ) {
        if (null != $this->redis){
            return $this->redis->zAdd( $key, $score, $value );
        }
        return null;
    }

    public function set( $account, $value, $prefix="",$param="") {
        if (null != $this->redis){
            $key = $param.$prefix.$account;
            return $this->redis->set($key, $value );
        }
        return null;
    }

    public function get( $account, $prefix="", $param="") {
        if (null != $this->redis){
            $key = $param.$prefix.$account;
            return $this->redis->get( $key);
        }
        return null;
    }
	
	public function del( $key ) {
        if (null != $this->redis){
            return $this->redis->del($key);
        }
        return null;
    }

    public function hget( $h, $key ) {
        if (null != $this->redis){
            return $this->redis->hGet( $h, $key );
        }
        return null;
    }
	
	public function hdel( $h, $key ) {
        if (null != $this->redis){
            return $this->redis->hDel( $h, $key );
        }
        return null;
    }
	
	public function hset( $h, $key, $value ) {
        if (null != $this->redis){
            return $this->redis->hSet($h, $key, $value);
        }
        return null;
    }

    public function sadd($key,$member){
        if (null != $this->redis){
            return $this->redis->sAdd($key,$member);
        }
        return null;
    }

    public function sismember($key,$member){
        if (null != $this->redis){
            return $this->redis->sIsMember($key,$member);
        }
        return false;
    }

    public function zcard($key){
        if (null != $this->redis){
            return $this->redis->zcard($key);
        }
        return false;
    }

    public function zcount($key, $min, $max){
        if (null != $this->redis){
            return $this->redis->zcount($key, $min, $max);
        }
        return false;
    }

    public function zincrby($key, $increment, $member){
        if (null != $this->redis){
            return $this->redis->zincrby($key, $increment, $member);
        }
        return false;
    }

    public function zscore($key, $member){
        if (null != $this->redis){
            return $this->redis->zscore($key, $member);
        }
        return false;
    }
}
