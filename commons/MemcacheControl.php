<?php
class MemcacheControl{
    public static $mem;
    public static $server = "localhost";
    public static $port = 11211;
    
    public static function init() {        
	self::$mem = new Memcache;
	self::$mem->connect(self::$server, self::$port) or die("memcache server not connect.");
    }

    public static function set($key, $value, $expires=3600) {
        self::init();
	self::$mem->set($key, $value, 0, $expires);
        self::$mem->close();
    }
    
    public static function replace($key, $value, $expires=3600) {
        self::init();
	self::$mem->replace($key, $value, 0, $expires);
        self::$mem->close();
    }
    
    public static function get($key) {
        self::init();
	$value = self::$mem->get($key);
        self::$mem->close();
        return $value;
    }
    
    public static function delete($key) {
        self::init();
	self::$mem->delete($key);
        self::$mem->close();
    }
    
    public static function flush() {
        self::init();
	self::$mem->flush();
        self::$mem->close();
    }
}
?>
