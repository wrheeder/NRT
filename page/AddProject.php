<?php

class Page_AddProject extends Page_ApplicationPage {

    function init() {
        parent::init();
        $this->api->stickyGet('sel_node');
        $f = $this->add('Form');
        

        $existing_proj_on_this_site = $this->api->db->dsql()->table('project')->field('project_types_id')->where('site_id', str_replace('[Site]', '', $_GET['sel_node']))->do_getAll();
        $exclude_projs = array();
        foreach ($existing_proj_on_this_site as $used_proj) {
            $exclude_projs[] = $used_proj['project_types_id'];
        }
        
        
        $m_proj_type = $this->add('Model_ProjectTypes');
        if (count($existing_proj_on_this_site) != 0) {
            $m_proj_type->addCondition('id', 'not in', $exclude_projs);
        } 
        $existing_projects_cnt=$m_proj_type->count()->get()[0]['count(*)'];
        if($existing_projects_cnt>0){
            $f->add('View_Info')->set('Please select a project from dropdown below');
            $f->addField('DropDown','Project')->setModel($m_proj_type);
        }else {
            $f->add('View_Error')->set('There are no free projects avaialble for execution on this Site');
        }
        
    }

}