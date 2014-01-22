<?php

class page_index extends Page_ApplicationPage {

    public $opts = array("plugins" => array("html_data", "types", "context_menu", "ui"),
        "types" => array(
            "max_depth" => -2,
            "max_children" => -2,
            "valid_children" => array("drive"),
            "types" => array(
                "root" => array(
                    "valid_children" => array("Region", "Site", "Project"),
                    "icon" => array(
                        "image" => "./wr-addons/jsTree/js/themes/default/earth.png"
                    )
                ),
                "Region" => array(
                    "valid_children" => "none",
                    "icon" => array(
                        "image" => "./wr-addons/jsTree/js/themes/default/warehouse-star.png"
                    )
                ),
                "Site" => array(
                    "valid_children" => "none",
                    "icon" => array(
                        "image" => "./wr-addons/jsTree/js/themes/default/site.png"
                    )
                ),
                "Project" => array(
                    "valid_children" => "none",
                    "icon" => array(
                        "image" => "./wr-addons/jsTree/js/themes/default/transit-store.png"
                    )
                )
            )
        ),
        "progressive_render" => true
    );

    function init() {
        parent::init();
        $this->api->jui->addStaticInclude('myJSFuncs');
        $tabs = $this->add('Tabs');

        $this->api->stickyGet('search_fld');


        $form = $this->add('Form');
        $form->js(true)->hide();
        $sel_node = $form->addField('line', 'sel_node', null, 'Search');
        $search_fld_frm = $this->api->add('Form', null, 'search_fld')->addClass('Stacked');
        ;
        $tree = $search_fld_frm->add('jsTree/jsTree');
        $search_fld = $search_fld_frm->addField('NoLabelLine', 'search_fld')->set($_GET['search_fld'] ? $_GET['search_fld'] : 'None');
        $search_fld->setCaption("test");
        $filter_subm = $search_fld_frm->addSubmit('Filter Sites');
        $this->loadTree($tree, $sel_node, $_GET['search_fld'] ? $_GET['search_fld'] : 'None');

        if ($_GET['sel_node']) {
            $sel_node->set($_GET['sel_node']);
            $t = $tabs->addTabURL($this->api->url('Site', array('sel_node' => $_GET['sel_node'])), 'Site');
            $p = $tabs->addTabURL($this->api->url('Projects', array('sel_node' => $_GET['sel_node'])), 'Projects');
            //die(var_dump(strpos($sel_node, '[Region]')));
            if ($sel_node->get() == '[Root]0000' || !(strpos($_GET['sel_node'], '[Region]') === false)) {
                $tabs->js(true)->hide();
            } else {
//                if (!$t)
//                    $t = $tabs->addTabURL($this->api->url('Site', array('sel_node' => $_GET['sel_node'])), 'Site');
//                if (!$p)
//                $p = $tabs->addTabURL($this->api->url('Projects', array('sel_node' => $_GET['sel_node'])), 'Projects');
                $tabs->js(true)->show();
            }
        }

        if ($form->isSubmitted()) {
            $js = array();
            $js[] = $this->js()->reload(array('sel_node' => $sel_node->get(), 'search_fld' => $search_fld->get()));
            $this->js(true, $js)->univ()->successMessage('Submitted')->execute();
        }
        if ($search_fld_frm->isSubmitted()) {
            $js[] = $search_fld_frm->js()->reload(array('sel_node' => $sel_node->get(), 'search_fld' => $search_fld->get()));
            $search_fld_frm->js(true, $js)->univ()->successMessage('Submitted')->execute();
        }
        $this->js(true)->univ()->successMessage('Index Loaded');
    }

    function loadTree($tree, $sel_store, $search_fld = 'None') {
        $m_usr = $this->add("Model_UserRegions")->addCondition('users_id', $this->api->auth->model->id)->setOrder('regions_id');
        $m_reg = $m_usr->join("Regions");
        $regions = array();
        $src = array();
        $src[] = array('ids' => '[Root]0000', 'name' => 'Regions', 'rel' => 'root', 'parent_id' => null);
        $m_site = $this->add("Model_Site");

        $regions = array();
        foreach ($m_usr->getRows() as $reg) {
            $regions[] = $reg['regions_id'];
            $src[] = array('ids' => '[Region]' . $reg['regions_id'], 'name' => $reg['regions'], 'rel' => 'Region', 'parent_id' => '[Root]0000');
        }
        $m_site->addCondition('regions_id','in',$regions);
        if ($search_fld != 'None') {
            $m_site->addCondition('site_code', 'like', '%' . $search_fld . '%');
            $m_site->setOrder('site_code', 'asc'); //$m_site->dsql()->where(array('site_code'=>$search_fld,'site_name like'=>'%'.$search_fld.'%'))->order('site_code');;
            //die('here');
        } else {
            $m_site->setOrder('site_code', 'asc');
            ;
        }
        foreach ($m_site->getRows() as $site) {
            $src[] = array('ids' => '[Site]' . $site['id'], 'name' => $site['site_code'] . ' ' . $site['candidate_letter'] . '-' . $site['site_name'], 'rel' => 'Site', 'parent_id' => '[Region]' . $site['regions_id']);
        }
        $tree->setSource($src, $tree, $sel_store, $this->opts);
    }

}