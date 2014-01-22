<?php
class Model_Site extends Model_Table{
    public $entity_code = 'site';

    function init() {
        parent::init();
        
        $this->addField('site_code')->mandatory(true);
        $this->addField('candidate_letter')->mandatory(true);
        $this->addField('site_name')->mandatory(true);
        $this->hasOne('Regions',null,'region');
    }
}