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

        
    }

    function initLayout() {
       // $this->auth->check();
        parent::initLayout();
    }

}