<?php

//Firstly, create subclasses for the custom tables:


class User extends BaseModel {
    protected $table = 'users';
    protected $primaryKey = 'ID';

    public function orders() {
        return $this->hasMany('Order', 'user_id', 'ID');
    }
}

class Order extends BaseModel {
    protected $table = 'orders';
    protected $primaryKey = 'ID';

    public function user() {
        return $this->belongsTo('User', 'user_id', 'ID');
    }
}
// Example usages:
// Find by ID

$userModel = new User();
$user = $userModel->find(1);
// Get with conditions

$userModel = new User();
$users = $userModel->get(['name' => 'John']);
//Get All Records

$userModel = new User();
$allUsers = $userModel->getAll();
//Insert

$userModel = new User();
$insertedId = $userModel->insert(['name' => 'John', 'email' => 'john@example.com']);
//Update

$userModel = new User();
$affectedRows = $userModel->update(1, ['name' => 'John Updated']);
//Delete

$userModel = new User();
$affectedRows = $userModel->delete(1);
//Order By

$userModel = new User();
$userModel->orderBy('name', 'DESC');
$users = $userModel->getAll();
//Paginate

$userModel = new User();
$users = $userModel->paginate(10, 1);
//One-to-Many Relationship

$userModel = new User();
$user = $userModel->find(1);
$orders = $user->orders();
//Belongs To (One-to-Many Inverse)

$orderModel = new Order();
$order = $orderModel->find(1);
$user = $order->user();