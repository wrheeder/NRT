<?php
class Page_Projects extends Page_ApplicationPage{
    function init(){
        parent::init();
        $this->api->stickyGet('sel_node');
        $projs = $this->add('Model_Project');
        $projs->addCondition('site_id',str_replace('[Site]', '', $_GET['sel_node']));
        $proj_grid = $this->add('GRID');
        $proj_grid->setModel($projs);
        
        $frm=$this->add('Form');
         $frm->addButton('Add Project')->js('click')->univ()->frameURL('Add Project', $this->api->getDestinationURL('AddProject'));
    }
}