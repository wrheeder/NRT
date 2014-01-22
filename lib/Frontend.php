<?php

/**
 * Consult documentation on http://agiletoolkit.org/learn 
 */
class Frontend extends ApiFrontend {

    function init() {
        parent::init();
        // Keep this if you are going to use database on all pages
        $this->dbConnect();
        $this->requires('atk', '4.2.5');

        //$auth->allowPage(array('index'));
        // This will add some resources from atk4-addons, which would be located
        // in atk4-addons subdirectory.
        $this->addLocation('atk4-addons', array(
                    'php' => array(
                        'mvc',
                        'misc/lib',
                        'filestore',
                    )
                ))
                ->setParent($this->pathfinder->base_location);
        $this->pathfinder->addLocation('.', array('addons' => array('ds-addons', 'wr-addons')));
        // A lot of the functionality in Agile Toolkit requires jUI
        $this->add('jUI');
        $this->js()
                ->_load('atk4_univ')
                ->_load('ui.atk4_notify')
        ;
        $auth = $this->add('ApplicationAuth');
        $l = $this->add('menu/Menu_Dropdown', null, 'Menu'); // DON'T USE FIELD NAMED "ID", because it's already built-in Model class as auto-incremental
        $layout = $this->api->add('Layout/Layout');
        if ($this->page == "index") {
            $layout->show("west");
            $layout->toggle("west");
        }
        $menu = array();
//        $auth->allowPage('admin/changePasswordExt');
//     
        if ($auth->isLoggedIn()) {
            $menu[] = array('ids' => 0, 'page' => 'index', 'name' => 'Rollout', 'parent_id' => null);
            if ($auth->isAdmin()) {
                $menu[] = array('ids' => 3, 'page' => 'admin', 'name' => 'Admin', 'parent_id' => null);
            }
            $menu[] = array('ids' => 5, 'page' => 'logout', 'name' => 'Logout', 'parent_id' => null);
        } else {
            $layout->hide("west");
        }
        $l->setRelationFields('ids', 'parent_id');
        //$this->add('themeswitcher\Test','themeswitcher_test');


        $l->setSource($menu);
    }

    function initLayout() {
        $this->auth->check();
        parent::initLayout();
    }

}