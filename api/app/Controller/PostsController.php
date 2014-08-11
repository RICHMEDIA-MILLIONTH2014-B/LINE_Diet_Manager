<?php

App::uses('Controller', 'Controller');

/**
 * Post Controller 
 *
 * @package app.Controller
 */
class PostsController extends AppController {

	/**
	 * Set use database name.
	 * @var array $uses Database name.
	 */
	var $uses = array('User','Menu','MealRecode');

	/**
	 * Post method
	 *
	 * @param mixed $user_id User name.
	 */ 
	public function post($user_id = NULL){
		
		// Set response type 
		$this->response->type('application/json');
		
		// if not defined $user_id
		if ($user_id == NULL) {
			
			/** 
			 *  Generate User name (Use OpenSSL Random Pseudo Bytes)
			 *
			 *  - Method_id : 0
			 */
			$data['User']['name'] = bin2hex(openssl_random_pseudo_bytes(4));
			$result = $data;
			
			// Save User data
			if ($this->User->save($data)) {
				
				// create ststus code 
				$result += $this->code('0','Success : Create and Save user id','1','0');			
			} else {
			
				// create status code
				$result += $this->code('0','Error : Not Create and Save user id','1','0');						
			}
		}
		
		// if request method is POST
		if ($this->request->is('POST')) {
			
			// convert JSON to array (non object)
			$post_data = json_decode($this->request->input(),true);
			
			// find User 
			$result = $this->User->find('first',
				array(
					'conditions' => array(
						'name' => $user_id
					),
					'fields' => array(
						'id',
						'name',
						'created'
					)
				)
			);
			
			/**
			 * How to ( 使い方 )
			 *
			 * - Method_id : 1
			 * - Context : 0 
			 */
			if (strncmp("使い方",$post_data['value'],3) == 0) {
				
				// create return message
				$result['Message']['value'] = 
					"LINE ダイエット マネージャーの使い方</br>".
					"コロンの左側の文字を入力してください</br>".
					"使い方：コマンド一覧</br>".
					"食べた：食べたものの登録</br>".
					"　料理名：食べた料理を入力</br>".
					"とりけし：前回の記録を削除</br>".
					"　はい：削除する</br>".
					"　いいえ：削除しない</br>".
					"グラフ：グラフ表示用URLを返す</br>";
					
				// create status codes
				$result += $this->code('0','Success : return how to','1','1');
			
			/**
			 * Eat ( 食べた )
			 *
			 * - Mthod_id : 2
			 * - Context : 0
			 */
			} else if (strncmp("食べた",$post_data['value'],3) == 0) {
				
				// create return message
				$result['Message']['value'] = 
					"料理名を入力してください</br>".
					"例） 親子丼 ";
					
				// create status code
				$result += $this->code('0','Success : Tabeta command accepted.','1','2');
		 	
		 	/**
		 	 * Delete ( とりけし ) 
		 	 *
		 	 * - Method_id : 3
		 	 * - Context : 0
		 	 */
		 	} else if (strncmp("とりけし",$post_data['value'],4) == 0) {
				
				// find mead data
				$meal_data = $this->MealRecode->find(
					'first',
					array(
						'conditions' => array(
							$result['User']['id']
						),
						'order' => array(
							'MealRecode.created' => 'desc'
						)
					)
				);
				
				// create return message
				$result['Message']['value'] = 
					"直前のメニュー登録（".$meal_data['Menu']['name']."）</br>".
					"をとりけしますか？</br></br>".
					"取り消す場合 → はい</br>".
					"取り消さない場合 ｰ> いいえ</br>";
					
				// create ststus codes
				$result += $this->code('0','accept return command.','1','3');
				
				// added meal_data
				$result += $meal_data;
		 	
		 	/**
		 	 * Graph ( グラフ )
		 	 *
		 	 * - Method_id : 4
		 	 * - Context : 0
		 	 */
		 	} else if (strncmp("グラフ",$post_data['value'],3) == 0) {
		 		
		 		// create return message
		 		$result['Message']['value'] = 
		 			"グラフ画面のURLを送信します </br>".
		 			" http://153.121.71.172/userlog.php?name=".$user_id.
		 			" </br>URLをクリックすると表示できます。";
		 			
		 		// create status code
		 		$result += $this->code('0','Success : return URL ','1','4');
		 		
			 /**
			  * Induction to Help ( ヘルプ )
			  *
			  * - Method_id : --
			  * - Context : --
			  */
			} else {
				$result['Message']['value'] = 
						"使い方 でヘルプが表示できます。";
			}
			if ($post_data['context'] == '1') {
				/**
				 * Eat ( たべた )  ②
				 *
				 * - Method_id : 2
				 * - Context : 1
				 */
				if (($post_data['method_id'] == '2')) {
					
					// Find Menu 
					$find_result = $this->Menu->find(
						'first',
						array(
							'conditions' => array(
								'name like' => '%'.$post_data['value'].'%'
							),
							'fields' => array(
								'id',
								'name',
								'energy'
							)
						)
					);
					
					// if find menu 
					if ($find_result != null) {
						
						// create save data 
						$data['MealRecode']['menu_id'] = $find_result['Menu']['id'];
						$data['MealRecode']['user_id'] = $result['User']['id'];	
						
						// Save Recode 				
						if ($this->MealRecode->save($data)) {
							
							// Set base calorie
							$calorie = 2000;
							
							// Find todays recode 
							$recodes = $this->MealRecode->find(
								'all',
								array(
									'conditions' => array(
										'MealRecode.created >='=>date("Y-m-d 00:00:00",time()),
										'MealRecode.created <='=>date("Y-m-d 23:59:59",time()),
										'user_id' => $result['User']['id']
									)
								)
							);
							
							// limit calorie
							foreach($recodes as $recode) {
								$calorie = $calorie - $recode['Menu']['energy'];
								$today_calorie = $recode['Menu']['energy'];
							}
							
							// create status code 
							$result += $this->code('0','Success : Save Meal Recode.','2','2');		
							
							// create return message									
							$result['Message']['value'] = 
								$find_result['Menu']['name']."を記録しました。</br>".
								"今日の総カロリーは ".$today_calorie." です</br>".
								"あと " . $calorie . " カロリー摂取できます。";
						} else {
							
							// create status code
							$result += $this->code('-1','Error : Don\'t save Meal Recode.','2','2');
							
							// create return message
							$result['Message']['value'] = 
								$find_result['Menu']['name']."を記録できませんでした</br>";
						}
						$result += $find_result;
						
					// Not found Menu
					} else {
						
						// create return message
						$result['Message']['value'] = 
							$post_data['value']."は見つかりません";
							
						// create status code
						$result += $this->code('-1','Not found menus.','2','2');
					}
				}
				
				/**
				 * Delete ( とりけし ) ②
				 *
				 * - Method_id : 3
				 * - Context : 2
				 */
				if (($post_data['method_id'] == '3')) {
					
					// Agreement
					if (strncmp("はい",$post_data['value'],2) == 0) {
						
						// Find lase insert data 
						$meal_data = $this->MealRecode->find(
							'first',
							array(
								'conditions' => array(
									$result['User']['id']
								),
								'order' => array(
									'MealRecode.created' => 'desc'
								)
							)
						);
						
						// Set MealRecode id
						$this->MealRecode->id = $meal_data['MealRecode']['id'];
						
						// Delete 
						if ($this->MealRecode->delete()) {
							
							// Successed
							$result['Message']['value'] = "メニューの削除を行いました。";
							$result +=$this->code('0','Success : Delete save meal recode','3','2');
						} else {
						
							// Failed
							$result['Message']['value'] = "メニューの削除を行えませんでした";
							$result +=$this->code('0','Error : Delete save meal recode','3','2');
						}
					
					// Disagreement
					} else if (strncmp("いいえ",$post_data['value'],3) == 0) {
						
						// create return message
						$result['Message']['value'] = 
							"削除を行いませんでした。</br>";
						$result += $this->code('0','don\'t deleted save meal recode');
					}
				}
			}			
		}
		
		// Set $result to View 		
		$this->set('result',$result);
	}
}