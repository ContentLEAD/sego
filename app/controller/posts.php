<?php

class posts extends controller{

    public function index(){
        
        
        
        
        
        
        $this->fez->load->view('header',array('is_admin'=>$admin));
        $this->fez->load->view('posts/home');
        $this->fez->load->view('footer');
    
    }

} 