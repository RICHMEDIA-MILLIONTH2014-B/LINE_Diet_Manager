<?php
class RecodesController extends AppController {
	
	/**
	 * Set use database
	 *
	 * @var mixed $uses database name.
	 */
	var $uses = array('MealRecode','User');
	
	/**
	 * graph method 
	 *
	 * @param mixed $user_name username.
	 */
	public function graph($user_name = NULL) {
		
		// Set response type
		$this->response->type('application/json');
		
		// if not defined $user_name
		if ($user_name == NULL) {
			
			// create return code
			$this->code('-1','Error : Not define user name');
		}
		
		// find user data for $user_name
		$user_data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.name' => $user_name
				)
			)
		);
		
		// Get MealRecode 
		$recodes = $this->MealRecode->find(
			'all',
			array(
				'conditions' => array(
					'user_id' => $user_data['User']['id']
				),
				'limit' => '20',
				'order' => array(
					'MealRecode.created' => 'desc'
				)
			)
		);
		
		// 
		foreach ($recodes as $key => $recode) {

			// Convert array
			$recodes["_".$key] = $recodes[$key];
			unset($recodes[$key]);
			
			// convert date 
			$day[$key] = date("y/m/d",strtotime($recode['MealRecode']['created']));
			$calorie[$key] = $recode['Menu']['energy'];
			$calorie[$key] = $calorie[$key] * 3;
		}
		
		// create return data.
		$result['GraphData'] = array(
			'day' => array(
				$day[4],
				$day[3],
				$day[2],
				$day[1],
				$day[0]
			),
			'calorie' => array(
				$calorie[4],
				$calorie[3],
				$calorie[2],
				$calorie[1],
				$calorie[0]
			)
		);
		$result += $recodes;
		
		// Set View 
		$this->set('result',$result);
	}
}