<?php
/**
 * Helper class for pharma project
 */
namespace common\helpers;

class Pharma {
	/**
	 * Get text representation for person as array|object
	 * @param array|object #person
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
								isset($person->birthdate)?$person->birthdate:'');
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
