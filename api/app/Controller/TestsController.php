<?php

/**
 * Test Controller 
 */
class TestsController extends AppController {
	
	/**
	 * test method 
	 *
	 */
	public function test() {
		
		// set response type
		$this->response->type('application/json');
		
		// Convert JSON to array (non object)
		$post_data = json_decode($this->request->input(),true);
		
		// create return message
		$result['Message'] = $post_data;
		$result += $this->code('0','This test method','0','0');
		
		// Set View
		$this->set('result',$result);	
	}

}