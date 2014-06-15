<?php
Class CurrentUser{
    public $sessionKey;
    public $userId;
    public $nick;
    public $expires;
    
    private $memUserIdKey = "userId";
    private $memExpiresKey = "expires";
    private $memNickKey = "nick";
    private $memSsessionKey = "sessionKey";
    
    function setExpires($expires){
        $_SESSION[$this->expires]=$expires;
    }
    
    function getExpires(){
        if(!$this->expires){
            $this->expires = $_SESSION[$this->memExpiresKey];
        }
        
        return $this->expires;
    }
    
    function setNick($nick){
        $_SESSION[$this->memNickKey]=$nick;
    }
    
    function getNick(){
        if(!$this->nick){
            $this->nick = $_SESSION[$this->memNickKey];
        }
        
        return $this->nick;
    }
    
    function setUserId($userId){
        $_SESSION[$this->memUserIdKey]=$userId;
    }
    
    function getUserId(){
        if(!$this->userId){
            $this->userId = $_SESSION[$this->memUserIdKey];
        }
        
        return $this->userId;
    }
    
    function setSession($sessionKey){
        $_SESSION[$this->memSsessionKey]=$sessionKey;
    }
    
    function delSession(){
        unset($_SESSION[$this->memSsessionKey]);
    }
    
    function getSession(){
        if(!$this->sessionKey){
            $this->sessionKey = $_SESSION[$this->memSsessionKey];
        }
        
        return $this->sessionKey;
    }
}

?>
