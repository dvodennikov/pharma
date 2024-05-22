<?php
namespace common\models;

trait traitDate {
	/**
     * Validate date fields
     * @param string $attribute
     * @param array $params
     */
    public function validateDate($attribute, $params)
    {
		$date = $this->$attribute;
		if (preg_match('/(\d{4})\-(\d{2})\-(\d{2})/', $date)) {
			if (isset($params['min'])) {
				if (self::dateToTimestamp($date) < self::dateToTimestamp($params['min'])) {
					$this->addError($attribute, \Yii::t('app', 'Date {date} must be greater than or equal to {min}', ['date' => $date, 'min' => $params['min']]));
				}
			}
			
			if (isset($params['max'])) {
				if (self::dateToTimestamp($date) > self::dateToTimestamp($params['max'])) {
					$this->addError($attribute, \Yii::t('app', 'Date {date} must be less than or equal to {max}', ['date' => $date, 'max' => $params['max']]));
				}
			}
		} else {
			$this->addError($attribute, \Yii::t('app', 'Wrong date: {date}', ['date' => $date]));
		}
	}
	
	/**
     * Convert date to timestamp
     * @param string date
     */
    public static function dateToTimestamp($date)
    {
		if (preg_match('/(\d{4})\-(\d{2})\-(\d{2})/', $date, $matches)) {
			return mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
		}
		
		return 0;
	}
}
