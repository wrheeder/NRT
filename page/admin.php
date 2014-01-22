<?php

class page_admin extends Page_ApplicationPage {

    function init() {
        parent::init();
        if ($this->api->auth->isAdmin()) {
            $tabs = $this->add('Tabs');
            $user = $tabs->addTab('Users')->add("CRUD");
            $regions = $tabs->addTab('Regions')->add("CRUD");
            $m_usr = $user->setModel('Users', array('email', 'name', 'surname', 'isAdmin','user_must_change_pw'));
            $m_regs = $regions->setModel('Regions');
            
            $this->api->stickyGet('id');
            if ($user->grid) {
                $user->grid->addQuickSearch(array('email','name','surname','user_must_change_pw'));
                $user->grid->getColumn('email')->makeSortable();
                $user->grid->getColumn('name')->makeSortable();
                $user->grid->getColumn('surname')->makeSortable();
                $user->grid->dq->order('email asc');
                $user->grid->addClass("zebra bordered");
                $user->grid->addPaginator(10);

                $user->grid->addColumn('button', 'changePassword');
                $user->grid->addColumn('expander', 'UserRegions');
                
                if ($_GET['changePassword']) {

                    // Get the name of currently selected member
                    $name = $user->grid->model->load($_GET['changePassword'])->get('username');

                    // Open frame with member's name in the title. Load content through AJAX from subpage
                    $this->js()->univ()->frameURL('Change Password for ' . $name, $this->api->url('admin/changePassword', array('id' => $_GET['changePassword'])))
                            ->execute();
                }
            }

            if ($user->form) {
                //$user->form->addField('password','password');
                if ($user->form->isSubmitted()) {
                    $m = $user->form->getModel();
                    if ($m['password'] == null || $m['password'] == '')
                        $m->set('password', $this->api->auth->encryptPassword('tempPW1234'));
                    $m->save();
                }
            }
        }
        
    }

}