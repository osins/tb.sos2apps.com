<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TBClient
 *
 * @author richar.wang
 */
class TBClient {
    protected $client;
    protected $session;
    protected $userId;
    protected $user;

    public function __construct($user) {
        $this->user = $user;
        $this->client = new TopClient;
        $this->client->appkey = TOP_APPKEY;
        $this->client->secretKey = TOP_SECRET;
        $this->session = $user->getSession();
        $this->userId = $user->getUserId();
    }
    
    public function execute($req){
        return $this->client->execute($req, $this->session);        
    }
}

?>
