<?php

class Model_Project extends Model_Table {

    public $entity_code = 'project';

    function init() {
        parent::init();
        $this->addField('project_start')->type('date');
        $this->addField('project_end')->type('date');
        $this->addField('created_on')->type('datetime');
        $site=$this->hasOne('Site');
        $usr=$this->hasOne('Users','created_by');
        $proj=$this->hasOne('ProjectTypes');
    }

}