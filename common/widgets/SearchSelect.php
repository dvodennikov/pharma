<?php

namespace common\widgets;

use \yii\helpers\Html;

class SearchSelect extends \yii\base\Widget {
	/** Container's Id for select element */
	public $id;
	/** URL for AJAX queries */
	public $url;
	/** Field name for input element to POST form's data */
	public $fieldName;
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
		
		//$this->initOptions();
		if (is_null($this->id)) {
			$this->id = \Yii::$app->security->generateRandomString();
		}
		
		if (is_null($this->url)) {
			throw new \yii\base\InvalidConfigException('No URL specified for AJAX queries');
		}
		
		if (is_null($this->fieldName)) {
			throw new \yii\base\InvalidConfigException('No field name specified for input element to POST form\'s data');
		}
		
		echo Html::beginTag('div', ['id' => $this->id]/*$this->options*/) . "\n";
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run() {
		$view = $this->getView();
		$view->registerJsFile('/js/search-select.js');
		$view->registerJs($this->renderInitScript());
		$view->registerCssFile('/css/search-select.css');
		
		echo "\n" . 'Search select';
		echo "\n" . Html::endTag('div');
		
		//$this->registerPlugin('search-select');
	}
	
	/**
	 * Render JS initialization for search-select code
	 */
	public function renderInitScript() {
		return 'new SearchSelect(' .
		       '"' . $this->id . '", ' .
		       '"' . $this->url . '", ' . 
		       '{' . 
					'"fieldName":"' . $this->fieldName . '"' . 
		       '});';
	}
}
