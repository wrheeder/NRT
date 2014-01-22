<?php
class Form_Field_NoLabelLine extends Form_Field_Line{
    function init() {
        parent::init();
        $this->js(true)->remove('label');
    }
    
    function defaultTemplate(){
        $this->addLocations(); // add addon files to pathfinder
        return array($this->template_file);
    }
}