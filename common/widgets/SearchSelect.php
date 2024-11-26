<?php

namespace common\widgets;

use \yii\helpers\Html;

class SearchSelect extends \yii\base\Widget {
	/** Container's Id for search select element */
	public $id;
	/** Container's class for search select element */
	public $class = 'form-control';
	/** URL for AJAX queries */
	public $url;
	/** AJAX parameter for search string */
	public $searchParam = 'search';
	/** Label for select field */
	public $label;
	/** Field name for input element to POST form's data */
	public $fieldName;
	/** Caption or hint for select field */
	public $caption;
	/** Value for select (hidden input field) field */
	public $value;
	/** Hint for search field */
	public $searchHint;
	/** Minimal number of characters to search */
	public $minCharsSearch = 3;
	/** Delay in ms before start search 
	 *  If input value changed cancel search and wait for new delay
	 */
	public $searchDelay = 0;
	/** Mimic CSS style
	 *  If mimic is true, copy CSS from first select field on the HTML page
	 *  If mimic is false, not copy CSS
	 *  If mimic is string, search first element on the HTML page.
	 *  For example '#person' or 'select.person_data'
	 */
	public $mimic;
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
		
		//$this->initOptions();
		if (is_null($this->id)) {
			$this->id = \Yii::$app->security->generateRandomString();
		}
		
		$this->id    = htmlspecialchars($this->id);
		
		if (is_null($this->url)) {
			throw new \yii\base\InvalidConfigException('No URL specified for AJAX queries');
		}
		
		if (is_null($this->fieldName)) {
			throw new \yii\base\InvalidConfigException('No field name specified for input element to POST form\'s data');
		}
		
		echo Html::beginTag('div', ['id'    => $this->id]/*$this->options*/) . "\n";
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
		$options = ['fieldName' => $this->fieldName];

		if (isset($this->class))
			$options['class'] = htmlspecialchars($this->class);
		if (isset($this->searchParam))
			$options['searchParam'] = htmlspecialchars($this->searchParam);
		if (isset($this->label))
			$options['label'] = htmlspecialchars($this->label);
		if (isset($this->value))
			$options['value'] = $this->value;
		if (isset($this->caption))
			$options['caption'] = htmlspecialchars($this->caption);
		if (isset($this->searchHint))
			$options['searchHint'] = htmlspecialchars($this->searchHint);
		if (isset($this->minCharsSearch))
			$options['minCharsSearch'] = $this->minCharsSearch;
		if (isset($this->searchDelay))
			$options['searchDelay'] = $this->searchDelay;
		if (isset($this->mimic))
			$options['mimic'] = $this->mimic;
			
		return 'new SearchSelect(' .
		       '"' . $this->id . '", ' .
		       '"' . $this->url . '", ' . 
		       json_encode($options) .
		       ');';
		return 'new SearchSelect(' .
		       '"' . $this->id . '", ' .
		       '"' . $this->url . '", ' . 
		       '{' . 
					'"fieldName":"' . $this->fieldName . '"' . 
					(isset($this->label)?(',"label":"' . htmlspecialchars($this->label) . '"'):'') .
					(isset($this->value)?(',"value":' . $this->value):'') .
					(isset($this->caption)?(',"caption":"' . htmlspecialchars($this->caption) . '"'):'') .
					(isset($this->searchHint)?(',"searchHint":"' . htmlspecialchars($this->searchHint) . '"'):'') .
					(isset($this->minCharsSearch)?(',"minCharsSearch":' . $this->minCharsSearch):'') . 
					(isset($this->searchDelay)?(',"searchDelay":' . $this->searchDelay):'') . 
					(isset($this->mimic)?(',"mimic":"' . $this->mimic . '"'):'') . 
		       '});';
	}
}
