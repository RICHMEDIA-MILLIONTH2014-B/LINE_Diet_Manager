<?php
class UsersController extends AppController {
	public function get_id() {
		$user_id = openssl_random_pseudo_bytes($TOKEN_LENGTH);
		$data['User']['name'] = $user_id;
		if ($this->User->save($data)) {
			return bin2hex($user_id);			
		} else {
			return NULL;
		}
	}
}