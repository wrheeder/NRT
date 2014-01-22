<?php

class Page_Site extends Page_ApplicationPage {

    function init() {
        parent::init();
        $m_site=$this->add('Model_Site');
        $m_site->tryLoad(str_replace('[Site]','',$_GET['sel_node']));
        $f = $this->add('Form'); 
        $f->setModel($m_site);
        
    }

}