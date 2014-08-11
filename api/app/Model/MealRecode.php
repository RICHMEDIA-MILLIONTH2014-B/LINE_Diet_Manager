<?php
class MealRecode extends AppModel {
	
	/**
	 *  Relation : belongsTo
	 */
	public $belongsTo = array('Menu');
}