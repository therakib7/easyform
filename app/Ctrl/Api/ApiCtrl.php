<?php
namespace Rhef\Ctrl\Api;


use Rhef\Ctrl\Api\Type\Dashbaord;
use Rhef\Ctrl\Api\Type\Email;
use Rhef\Ctrl\Api\Type\Form;
use Rhef\Ctrl\Api\Type\FormBuilder;
use Rhef\Ctrl\Api\Type\Media;
use Rhef\Ctrl\Api\Type\Setting;
use Rhef\Ctrl\Api\Type\Taxonomy;
use Rhef\Ctrl\Api\Type\Webhook;

class ApiCtrl
{

	public function __construct()
	{
		new FormBuilder();
		new Email();
		new Media();
		new Dashbaord();
		new Taxonomy();
		new Form();
		new Webhook();
		new Setting();
	}
}
