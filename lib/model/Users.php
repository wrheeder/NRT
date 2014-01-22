<?php

class Model_Users extends Model_Table {

    public $entity_code = 'users';

    function init() {
        parent::init();
        $this->addField('email')->mandatory('Email required');
        $this->addField('name')->mandatory('Name required');
        $this->addField('surname')->mandatory('Surname required');
        $this->addField('password')->display(array('form' => 'password'))->mandatory('Type your password');
        $this->addField('isAdmin')->type('boolean');
    }
    

}