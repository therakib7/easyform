<?php 
namespace Rhef\Ctrl\Assist;

use Rhef\Ctrl\Assist\Type\Feedback;
use Rhef\Ctrl\Assist\Type\Link;

class AssistCtrl {
	
	public function __construct() {   
		new Link(); 
		new Feedback();
	} 
}