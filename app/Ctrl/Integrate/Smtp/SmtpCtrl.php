<?php 
namespace Rhef\Ctrl\Integrate\Smtp;

use Rhef\Ctrl\Integrate\Smtp\SmtpList; 

class SmtpCtrl
{ 
	public function __construct()
	{ 
		new SmtpList(); 
	}
}
