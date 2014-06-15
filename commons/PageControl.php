<?php

Class PageControl{
    private $smarty;
    
    function __construct() {
        $this->smarty = new Smarty;

        $this->smarty->template_dir = 'templates/';
        $this->smarty->compile_dir = 'templates_c/';
        $this->smarty->config_dir = 'smarty/configs/';
        $this->smarty->cache_dir = 'smarty/cache/';
    }

    public function loadTpl($template){
        $this->smarty->display('Header.tpl');        
        $this->display($template);        
        $this->smarty->display('Footer.tpl');
    }
    
    public function display($template){
        $this->smarty->display($template);
    }
    
    public function assign($key, $value){
        $this->smarty->assign($key, $value);
    }
}
?>
