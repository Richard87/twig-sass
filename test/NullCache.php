<?php
namespace Richard87\NullCache;
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 16.11.2016
 * Time: 13.31
 */
class NullCachePool implements \Psr\Cache\CacheItemPoolInterface {
    public function getItem($key):NullCacheItem {return new NullCacheItem($key);}
    public function getItems(array $keys = array()) {
        foreach ($keys as $key)
            yield new NullCacheItem($key);
    }
    public function hasItem($key){return false;}
    public function clear(){}
    public function deleteItem($key){}
    public function deleteItems(array $keys){}
    public function save(\Psr\Cache\CacheItemInterface $item){}
    public function saveDeferred(\Psr\Cache\CacheItemInterface $item){}
    public function commit(){}
}
class NullCacheItem implements \Psr\Cache\CacheItemInterface {
    private  $key = null;
    public function __construct($key){$this->key = $key;}
    public function getKey(){return $this->key;}
    public function get(){return null;}
    public function isHit(){return false;}
    public function set($value){}
    public function expiresAt($expiration){}
    public function expiresAfter($time){}
}