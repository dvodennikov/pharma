<?php

namespace common\widgets;

use \yii\helpers\Html;

class SearchSelect extends \yii\base\Widget {
	/** Container's Id for select element */
	public $id;
	/** URL for AJAX queries */
	public $url;
	/** Label for select field */
	public $label;
	/** Field name for input element to POST form's data */
	public $fieldName;
	/** Caption or hint for select field */
	public $caption;
	/** Value for select (hidden input field) field */
	public $value;
	/** Minimal number of characters to search */
	public $minCharsSearch = 3;
	/** Delay in ms before start search 
	 *  If input value changed cancel search and wait for new delay
	 */
	public $searchDelay = 0;
	
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
					(isset($this->label)?(',"label":"' . htmlspecialchars($this->label) . '"'):'') .
					(isset($this->value)?(',"value":' . $this->value):'') .
					(isset($this->caption)?(',"caption":"' . htmlspecialchars($this->caption) . '"'):'') .
					(isset($this->minCharsSearch)?(',"minCharsSearch":' . $this->minCharsSearch):'') . 
					(isset($this->searchDelay)?(',"searchDelay":' . $this->searchDelay):'') . 
		       '});';
	}
}
