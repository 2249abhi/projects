<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Sidbi cell
 */
class SidbiCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $this->loadModel('LogoSliders');
        $logoSliderData = $this->LogoSliders->find('all',['conditions'=>['status'=>1,'logo_cat_id'=>2]])->enableHydration(false)->toArray();
        $this->set(compact('logoSliderData'));
        // $cell = $this->cell('Sidbi');
        // $cell->template = 'messages';
        // echo $cell;
    }
}
