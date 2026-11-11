<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Tables_model');
		$this->load->model('Categories_model');
		$this->load->model('Products_model');
		$this->load->model('Cart_model');
		$this->load->model('Discounts_model');
		$this->load->model('Orders_model');
		$this->load->model('Kitchen_model');
		$this->load->model('Prints_model');
    }

    public function index()
    {
		$tables = $this->Tables_model->getall();
		$cart_data = $this->Cart_model->getall();
		$cartStatus = $this->Cart_model->getTransactionTableStatus();
		
		// $cartStatus = "0";
		// foreach($cart_data as $item){
			// $cartStatus .=$item->ctStatus;
			
		// }

		$cart = array();
		foreach($cart_data as $item){
			$cart[$item->ctTable][] = $item;
		}
		
		if(isset($_GET['test'])){
			echo $cartStatus;
			return false;
		}
		
		// echo $cartStatus;
		// return false;

		$data['cart'] = $cart;
		$data['tables'] = $tables;
		$data['cartStatus'] = $cartStatus;
		$data['title'] = 'transactions';
		$this->load->view('templates/top', $data);
		$this->load->view('pos/transactions/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function cart($table_id){
		$tables = $this->Tables_model->getall();
		$data['tables'] = $tables;
		$data['title'] = 'transactions';
		$this->load->view('templates/top', $data);
		$this->load->view('pos/transactions/cart', $data);
		$this->load->view('templates/bottom');
	}
	
	public function checkout($table_id, $order_id = false){
		$cart = $this->Cart_model->getCartByTable($table_id);
		$table = $this->Tables_model->get($table_id);
	

		$data['table_id'] = $table_id;
		$data['table'] = $table;
		$data['cart'] = $cart;
		$data['order_id'] = $order_id;
		$data['title'] = 'transactions';
		$this->load->view('templates/top', $data);
		$this->load->view('pos/transactions/checkout', $data);
		$this->load->view('templates/bottom');
	}
	
	public function print_order($order_id){
		if (!$order_id || !$this->Orders_model->get($order_id)) {
			$this->session->set_flashdata('error', 'Order not found');
			redirect('pos/transactions');
			return;
		}
		$this->Prints_model->add(1, $order_id, null);
		$this->session->set_flashdata('success', 'Receipt added to the print queue');
		redirect('pos/transactions/checkout/' . (int) $this->Orders_model->get($order_id)->odrTable . '/' . (int) $order_id);
	}

	public function order($table_id){
		$tables = $this->Tables_model->getall();
		$table = $this->Tables_model->get($table_id);
		$categories = $this->Categories_model->getall();
		$products = $this->Products_model->getall();
		$cart = $this->Cart_model->getCartByTable($table_id);
		$all_cart = $this->Cart_model->getAll();

		$occupied_table[] = $table_id;
		foreach($all_cart as $cart_search){
			if (!in_array($cart_search->ctTable, $occupied_table)) {
				$occupied_table[] = $cart_search->ctTable;
			}
		}

		$category_list = array();
		foreach($categories as $category){
			$category_list[$category->catID] = $category->catName;
		}
		
		$product_list = array();
		foreach($products as $product){
			$product_list[$product->prodCategory]['name'] = $category_list[$product->prodCategory];
			$product_list[$product->prodCategory]['products'][] = $product;
		}
		
		$discounts = $this->Discounts_model->getall();

		$cartStatus = $table_id;
		foreach($cart as $item){
			$cartStatus .=$item->ctStatus;
		}

		// echo $cartStatus;
		// return false;

		$data['cart'] = $cart;
		$data['cartStatus'] = $cartStatus;
		$data['discounts'] = $discounts;
		$data['table_id'] = $table_id;
		$data['table'] = $table;
		$data['tables'] = $tables;
		$data['product_list'] = $product_list;
		$data['categories'] = $categories;
		$data['occupied_table'] = $occupied_table;
		$data['title'] = 'transactions';
		$this->load->view('templates/top', $data);
		$this->load->view('pos/transactions/order', $data);
		$this->load->view('templates/bottom');
	}
	public function add_cart($tableID = false){
		if($tableID && isset($_POST)){
			$postData = $_POST;
			// $qty = $postData['qty'];
			// for($x == 0; $x < $qty; $x++){
				$products = $this->Products_model->get($postData['product']);
				$postData['price'] = $products->prodPrice * $postData['qty'];
				// $postData['price'] = $products->prodPrice;
				// $postData['qty'] = 1;
				// update inventory
				$updateData['prodQty'] = $products->prodQty - $postData['qty'];
				// $updateData['prodQty'] = $products->prodQty - 1;
				$this->Products_model->update($postData['product'], $updateData);
				
				$userdata = $this->session->Userdata('login');
				$postData['waiter'] = $userdata->urID;
				
				$cart_entry = $this->Cart_model->do_addToCart($postData);
			}
			if($cart_entry){
				
				add_log('add product', json_encode($postData));
				$this->session->set_flashdata('success', 'Item has been added to cart');
				redirect('pos/transactions/order/'.$tableID, 'location');
				return false;
			}
		// }
		$this->session->set_flashdata('error', 'Failed to add to cart');
		redirect('pos/transactions/order/'.$tableID, 'location');
	}
	public function order_ajax($prodID = false){
		if($prodID){
			$product = $this->Products_model->get($prodID);
			if($product){
				echo json_encode($product);
			}
		}
	}
	
	public function add_discount($cartID){
		if($cartID && isset($_POST)){
			$cart = $this->Cart_model->get($cartID);
			$product = $this->Products_model->get($cart->ctProduct);
			
			$discountID = $_POST['discount'];
			if($discountID != 0){
				$discount = $this->Discounts_model->get($discountID);
				$temp_discount = $discount->disPercent;
			}else
				$temp_discount = 0;
			
			
			$current_price = $product->prodPrice * $cart->ctQty;
			$discount_percent = ($temp_discount / 100);
			$discount_value = $discount_percent * $current_price;
			$new_price = $current_price - $discount_value;
			
			// update
			$update['ctSales'] = $new_price;
			$update['ctDiscount'] = $discountID;
			$this->Cart_model->update($cartID, $update);
			
			// echo "<pre>";
			// print_r($discount);
			// print_r($product);
			// print_r($cart);
			// echo "</pre>";
			$log['cart id'] = $cartID;
			$log['product id'] = $cart->ctProduct;
			$log['discount id'] = $discountID;
			$log['data'] = $update;
			add_log('add discount', json_encode($log));
			$this->session->set_flashdata('success', 'Discount was updated');
			redirect('pos/transactions/order/'.$cart->ctTable);
			return false;
		}
		$this->session->set_flashdata('error', 'Failed to add discount');
		redirect('pos/transactions');
	}
	
	public function remove_cart($table_id = false, $cartID = false){
		if($table_id && $cartID){
			
			$cart = $this->Cart_model->get($cartID);
			$product = $this->Products_model->get($cart->ctProduct);
			
			$updateData['prodQty'] = $cart->ctQty + $product->prodQty;
			$this->Products_model->update($cart->ctProduct, $updateData);

			$cart = $this->Cart_model->do_delete($cartID);
			
			$log['table id'] = $table_id;
			$log['cart id'] = $cartID;
			$log['product id'] = $cart->ctProduct;
			$log['data'] = $updateData;
			add_log('remove cart item', json_encode($log));
			$this->session->set_flashdata('success', 'Item has been remove from cart');
			redirect('pos/transactions/order/'.$table_id);
			return false;
		}
		$this->session->set_flashdata('error', 'Failed to remove item from cart');
		redirect('pos/transactions');
	}
	
	public function do_checkout($table_id){
		if(isset($_POST)){
			$postData = $_POST;
			$cart = $this->Cart_model->getCartByTable($table_id);
			$postData['cart_data'] = json_encode($cart);

			// echo "<pre>";
			// print_r($postData);
			// // print_r($cart);
			// echo "</pre>";
			
			$sales = 0;
			$price = 0;
			$table_name = "Unknown";
			foreach($cart as $item){
				$sales = $sales + $item->ctSales;
				$price = $price + $item->ctPrice;
				$table_name = $item->tbName;
			}
			
		
			$postData['other_charges'] = $postData['other_charges'] != "" ? str_replace( ',', '', $postData['other_charges']) : 0;
			$postData['add_discount'] = $postData['add_discount'] != "" ? str_replace( ',', '', $postData['add_discount']) : 0;
			$postData['payment'] = $postData['payment'] != "" ? str_replace( ',', '', $postData['payment']) : 0;
			$postData['change'] = $postData['change'] != "" ? str_replace( ',', '', $postData['change']) : 0;
			// $postData['add_discount'] = str_replace( ',', '', $postData['add_discount'] );
			// $postData['payment'] = str_replace( ',', '', $postData['payment'] );
			// $postData['change'] = str_replace( ',', '', $postData['change'] );
			
			$price = $price + $postData['other_charges'];
			$sales = $sales + $postData['other_charges'];
						
			$discount = $price - $sales + $postData['add_discount'];

			$postData['discount'] = $discount;
			$postData['price'] = $price;
			
			$userdata = $this->session->Userdata('login');
			$postData['casher'] = $userdata->urID;

			$total = $price - $discount;
			
			

			$postData['sales'] = $sales;
			$postData['table'] = $table_id;
			$postData['customer_name'] = $postData['customer_name'] != ""? $postData['customer_name'] : "Anonymous";
			
			$orderID = $this->Orders_model->do_add($postData);
			
			$log['table id'] = $table_id;
			$log['order id'] = $orderID;
			$log['data'] = $postData;
			add_log('cart checkout', json_encode($log));
			
			// $orderID = true;
			if($orderID){
				// Type 1 = payment receipt; prtCartID stores the completed order ID.
				$this->Prints_model->add(1, $orderID, null);
				$this->Cart_model->do_deleteByTable($table_id);
				
				//$this->session->set_flashdata('print', 'do reprint');
				$this->session->set_flashdata('success', 'Checkout success');
				redirect('pos/transactions/checkout/'.$table_id.'/'.$orderID);
	
				/*
				// do printer print here
				$data['other_charges'] = $postData['other_charges'];
				$data['add_discount'] = $postData['add_discount'];
				$data['payment'] = $postData['payment'];
				$data['payment'] = $postData['payment'];
				$data['change'] = $postData['change'];
				$data['total_sales'] = $price;
				$data['discount'] = $discount;
				$data['total'] = $total;
				$data['cart'] = $cart;
				$data['table_id'] = $table_id;
				$data['table_name'] = $table_name;
				$this->load->view('pos/transactions/print_order2', $data);
				*/
				
				
				return false;
				// $this->load->model('Prints_model');

				// // save print table
				// $print['name'] = "Print checkout";
				// $print['type'] = 2;
				// $print['table'] = $table_id;
				// $print['data'] = json_encode($data);
				// $this->Prints_model->do_add($print);
				
				// $this->session->set_flashdata('success', 'Print checkout was printed');
				// redirect('pos/transactions');
				
				
				return false;
			}
		}
		
		$this->session->set_flashdata('error', 'Failed to checkout');
		redirect('pos/transactions/checkout/'.$table_id);
	}
	
	public function change_table($table_id){
		if(isset($_POST)){
			$new_table = $_POST['table_change'];
			$postData['ctTable'] = $new_table;
			$this->Cart_model->update_byTable($table_id, $postData);
			
			$log['table id from'] = $table_id;
			$log['table id to'] = $new_table;
			add_log('change table', json_encode($log));
			
			$this->session->set_flashdata('success', 'Table was moved');
			redirect('pos/transactions/order/'.$new_table);
			return false;
		}
		$this->session->set_flashdata('error', 'Failed to change table');
		redirect('pos/transactions/order/'.$table_id);
	}
	
	public function order_kitchen($table_id){
		$unserved_cart = $this->Cart_model->getUnserved($table_id);
		
		if(count($unserved_cart)){
			$cart_items_id = array();
			foreach($unserved_cart as $items){
				$cart_items_id[] = $items->ctID;
				$this->Cart_model->update($items->ctID, array('ctStatus'=>1, 'ctTimeOrder'=>date('Y-m-d H:i:s')));
				
				$kitchen['kitCart'] = $items->ctID;
				$kitchen['kitTable'] = $items->ctTable;
				$kitchen['kitProduct'] = $items->ctProduct;
				$kitchen['kitQty'] = $items->ctQty;
				$kitchen['kitStatus'] = 1;
				$kitchen['kitTimeOrdered'] = date('Y-m-d H:i:s');
				$kitchenID = $this->Kitchen_model->add($kitchen);
				if ($kitchenID) {
					// Type 3 = kitchen ticket, linked to tbl_kitchen.kitID.
					$this->Prints_model->add(3, null, $kitchenID);
				}
			}
			
			if(count($cart_items_id)){

				$this->session->set_flashdata('success', 'Order was sent to kitchen');
				redirect('pos/transactions/order/'.$table_id);
				return false;
			}
		
			$this->session->set_flashdata('error', 'Failed to send order in kitchen');
			redirect('pos/transactions/order/'.$table_id);
		}else{
			$this->session->set_flashdata('error', 'Failed to send order in kitchen, no order pending');
			redirect('pos/transactions/order/'.$table_id);
		}

	}
	
	public function print_preview($table_id){
		$cart = $this->Cart_model->getCartByTable($table_id);
		if (empty($cart)) {
			$this->session->set_flashdata('error', 'There are no items to preview');
			redirect('pos/transactions/order/' . (int) $table_id);
			return;
		}
		// Type 2 = order preview; prtCartID stores the table ID so the terminal
		// can print the complete current cart as one receipt.
		$this->Prints_model->add(2, isset($cart[0]->ctID) ? $cart[0]->ctID : null, null, array('table_id' => (int) $table_id, 'cart' => $cart));
		$this->session->set_flashdata('success', 'Order preview added to the print queue');
		redirect('pos/transactions/order/' . (int) $table_id);
	}

	public function deliver_order($table_id, $itemID){
		$this->Cart_model->update($itemID, array('ctStatus'=>4, 'ctTimeDeliver'=>date('Y-m-d H:i:s')));
		$this->Kitchen_model->update($itemID, array('kitStatus'=>4, 'kitTimeDelivered'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('success', 'Order was served');
		redirect('pos/transactions/order/'.$table_id);
	}
}
