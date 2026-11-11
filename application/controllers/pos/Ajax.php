<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Cart_model');
        
    }

	public function checkCartStatus(){
		$cartStatus = 0;
		$tableID = $_POST['tableID'];
		if($tableID){
			$cart_data = $this->Cart_model->getCartByTable($tableID);
			$cartStatus = $tableID;
			foreach($cart_data as $item){
				$cartStatus .=$item->ctStatus;
			}
		
		}
		echo $cartStatus;
	}
	
	public function checkCartStatusKitchen(){

		$cart_data = $this->Cart_model->getProductKitchen();
		// $products = $this->Cart_model->getProductKitchen();
		$cartStatus = "0";
		foreach($cart_data as $item){
			$cartStatus .=$item->ctStatus;
		}
		echo $cartStatus;
	}

	public function checkTransactionItemStatus(){
		// $cart_data = $this->Cart_model->getAll();
		// $cartStatus = "";
		// foreach($cart_data as $item){
			// $cartStatus .=$item->ctStatus;
		// }
		// echo $cartStatus;
		
		$cartStatus = $this->Cart_model->getTransactionTableStatus();
		echo $cartStatus;
	}
	
	public function checkUnprinted(){
		$this->load->model('Prints_model');
		$unprinted = $this->Prints_model->getUnprinted();
		echo count($unprinted);
	}

	public function checkUnprintedOnline(){
		echo getDataOnline("https://stonehavensys.com/ajax/checkUnprinted");
	}
	
	public function checkTransactionItemStatusOnline(){
		echo getDataOnline("https://stonehavensys.com/ajax/checkTransactionItemStatus");
	}
	
	public function checkCartStatusOnline(){
		$tableID = $_POST['tableID'];
		echo getDataOnline("https://stonehavensys.com/ajax/checkCartStatus/".$tableID);
	}
	
	public function checkCartStatusKitchenOnline(){
		echo getDataOnline("https://stonehavensys.com/ajax/checkCartStatusKitchen");
	}
	
	
	

}
