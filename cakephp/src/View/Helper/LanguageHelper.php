<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
class LanguageHelper extends Helper
{
	private $Languages;
	private $localeSources;
	private $localeTargets;
	private $Session;
	private $LanguageLabels;
	public function initialize(array $config)
	{
		//$this->Session 			= $this->request->getSession();
		$this->Languages 		= TableRegistry::get('languages');
		$this->localeSources 	= TableRegistry::get('locale_sources');
		$this->localeTargets 	= TableRegistry::get('locale_targets');
	}
		
	public function getLanguageLabels($englishName, $cultureType = 'hi')
	{
		$LanguageLabels = $englishName;
		$localeSources = $this->localeSources->find('all',[
			'conditions' => ['source' => trim($englishName)],
			'fields' => ['id']]
		)->first();

        if(empty($localeSources)){
			$sourcesData = $this->localeSources->newEntity();
			$sourcesData->source	= trim($englishName); 
			$this->localeSources->save($sourcesData);
		}
		
		if(!empty($localeSources)){
			$localeTargets = $this->localeTargets->find('all',[
				'conditions' => ['locale_source_id'	=> $localeSources->id,'language'=>$cultureType],
				'fields' => ['translation']]
			)->first();

			if(!empty($localeTargets)){
				$LanguageLabels = $localeTargets->translation;
			} else {
				$LanguageLabels = $englishName;
			}
		}
		return $LanguageLabels;
	}
}