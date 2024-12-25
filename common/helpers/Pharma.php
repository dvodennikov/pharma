<?php
/**
 * Helper class for pharma project
 */
namespace common\helpers;

use yii\helpers\Html;
use yii\helpers\Url;

class Pharma {
	/**
	 * Get text representation for person as array|object
	 * @param array|object $person
	 * @param string format
	 * @return string
	 */
	public static function getTextRepresentationForPerson($person, $format = null) 
	{
		if (is_array($person)) {
			$person = (object) $person;
		} elseif (is_object($person)) {
		} else {
			return '';
		}
		
		if (is_null($format)) {
			$format = '';
			
			foreach (['surname', 'name', 'secondname'] as $field)
				$format .= isset($person->$field)?(((strlen($format) == 0)?'':' ') . '%s'):'%s';
				
			$format .= isset($person->birthdate)?(((strlen($format) == 0)?'':' ') . '(%s)'):'%s';
		}
		
		return sprintf($format, isset($person->surname)?$person->surname:'',
								isset($person->name)?$person->name:'',
								isset($person->secondname)?$person->secondname:'',
								isset($person->birthdate)?\Yii::$app->getFormatter()->asDate($person->birthdate, 'long'):'');
	}
	
	/**
	 * Get text representation for Drug
	 * @param array|object $drug
	 * @return string
	 */
	public static function getTextRepresentationForDrug($drug)
	{
		if (is_array($drug)) {
			$drug = (object) $drug;
		} elseif (is_object($drug)) {
		} else {
			return '';
		}
		
		return $drug->title . (isset($drug->description)?(' [' . $drug->description . ']'):'');
	}
	
	/**
	 * Get text representation for Receipt
	 * @param array|object $receipt
	 * @param array $params
	 * @return string
	 */
	public static function getTextRepresentationForReceipt($receipt, $params)
	{
		if (is_array($receipt)) {
			$receipt = (object) $receipt;
		} elseif (is_object($receipt)) {
		} else {
			return '';
		}
		
		$text = $receipt->number . (isset($receipt->person)?(' ' . Pharma::getTextRepresentationForPerson($receipt->person)):'');
		
		if (isset($params['url']))
			$text = Html::a($text, $params['url']);
		
		if (isset($receipt->drugs)) {
			$drugs = '';
			foreach ($receipt->drugs as $drug)
				$drugs .= '<li>' . $drug . '</li>';
			
			$text .= '<ul>' . $drugs . '</ul>';
		}
		
		return $text;
	}
	
	/**
     * Convert date to timestamp
     * @param string $date
     * @return string
     */
    public static function dateToTimestamp($date)
    {
		if (preg_match('/(\d{4})\-(\d{2})\-(\d{2})/', $date, $matches)) {
			return mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
		}
		
		return 0;
	}
	
	/**
	 * Get HTML for language switching
	 * @param string[] $languages
	 * @return string
	 */
	public static function getLanguageSwitchingHtml($languages = null)
	{
		if (!is_array($languages))
			$languages = ['en-US', 'ru-RU'];
			
		$currentLanguage = \Yii::$app->language;//\Yii::$app->session->get('language', 'en-US');
		$currentUrl      = \Yii::$app->request->getUrl();
		$currentUrl      = substr($currentUrl, 0, (stripos($currentUrl, '?') === false)?strlen($currentUrl):stripos($currentUrl, '?'));
		$params          = \Yii::$app->request->get();
		$paramsUrl       = '';
		
		foreach ($params as $param => $value)
			if ($param !== 'language')
				$paramsUrl .= '&' . urlencode($param) . '=' . urlencode($value);
		
		$html            = '<div class="languages-switching">';
		
		foreach ($languages as $language) {
			$params['language'] = $language;
			$html .= '<span' . (($currentLanguage == $language)?' class="language-active"':'') . '>' . 
			         //Html::a(mb_substr($language, 0, 2), Url::to([$currentUrl, 'language' => $language])) .
			         Html::a(mb_substr($language, 0, 2), $currentUrl . '?' . $paramsUrl . '&language=' . $language) .
			         '</span>';
		}
		
		return $html . '</div>';
	}
}
