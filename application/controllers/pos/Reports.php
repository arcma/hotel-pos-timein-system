<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Orders_model');
    }

    public function index()
    {

		$days = isset($_GET['days'])? $_GET['days'] : 1;
		$from = date('Y-m-d',strtotime('-'.$days.' day'))." 00:00:00";
		$to = date('Y-m-d H:i:s',strtotime('now'));
		
		$orders = $this->Orders_model->getByDateRange($from, $to);
		
		$chart_data = array();
		foreach($orders as $order){
			$date_created = date('F j', strtotime($order->odrDate_created));
			$chart_data[$date_created] = isset($chart_data[$date_created])? $chart_data[$date_created] + (int)$order->odrSales : (int)$order->odrSales;
		}
		
		$chart = array();
		for($x = $days; $x >= 0; $x--){
			$date = date('F j',strtotime('-'.$x.' day'));
			$chart[$date] = isset($chart_data[$date])? $chart_data[$date] : 0;
		}
		
		// echo "<pre>";
		// print_r($chart);
		// // print_r($orders);
		// echo "</pre>";
		// return false;
		
		$data['orders'] = $orders;
		$data['chart'] = $chart;
		$data['title'] = 'reports';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/reports/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function sales_details()
	{
		list($from, $to) = explode(' - ',$_GET['date']);
		
		$from = date('Y-m-d H:i:s',strtotime($from));
		$to = date('Y-m-d H:i:s',strtotime($to . ' +1 day'));
		$orders = $this->Orders_model->getByDateRange($from, $to);

		$data['title'] = 'reports';
		$data['orders'] = $orders;
		$data['date'] = $_GET['date'];
        $this->load->view('templates/top', $data);
		$this->load->view('pos/reports/sales_details', $data);
		$this->load->view('templates/bottom');
	}
	
	public function product_details()
	{
		list($from, $to) = explode(' - ',$_GET['date']);
		$from = date('Y-m-d H:i:s',strtotime($from));
		$to = date('Y-m-d H:i:s',strtotime($to . ' +1 day'));
		
		$orders = $this->Orders_model->getProductOrderByDateRange($from, $to);
		
		// echo "<pre>";
		// print_r($orders);
		// echo "</pre>";
		
		$cart = array();
		$item_count = array();
		$price_total = array();
		$discount_total = array();
		$sales_total = array();
		foreach($orders as $order){
			$cart[$order->prProduct]['name'] = $order->prodName;
			if(isset($cart[$order->prProduct]['count'])){
				$cart[$order->prProduct]['count'] = $cart[$order->prProduct]['count'] + $order->prQty;
			}else{
				$cart[$order->prProduct]['count'] = $order->prQty;
			}
			
			if(isset($cart[$order->prProduct]['price'])){
				$cart[$order->prProduct]['price'] = $cart[$order->prProduct]['price'] + $order->prPrice;
			}else{
				$cart[$order->prProduct]['price'] = $order->prPrice;
			}
			
			
			if(isset($cart[$order->prProduct]['discount'])){
				$cart[$order->prProduct]['discount'] = $cart[$order->prProduct]['count'] + $order->prDiscount;
			}else{
				$cart[$order->prProduct]['discount'] = $order->prDiscount;
				
			}
			
			if(isset($cart[$order->prProduct]['sales'])){
				$cart[$order->prProduct]['sales'] = $cart[$order->prProduct]['sales'] + $order->prSales;
			}else{
				$cart[$order->prProduct]['sales'] = $order->prSales;
			}

			$cart[$order->prProduct]['data'][] = $order;
			// echo "<pre>";
			// print_r($order);
			// echo "</pre>";
			
		}
		

// return false;
		$data['cart'] = $cart;
		$data['orders'] = $orders;
		$data['title'] = 'reports';
		$data['date'] = $_GET['date'];
        $this->load->view('templates/top', $data);
		$this->load->view('pos/reports/product_details', $data);
		$this->load->view('templates/bottom');
	}
	
	public function inventory_details()
	{
		$this->load->model('Products_model');
		$products = $this->Products_model->getAll();
		
		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";
		
		// return false;
		
		$data['date'] = date('d/m/Y');
		$data['title'] = 'reports';
		$data['products'] = $products;
        $this->load->view('templates/top', $data);
		$this->load->view('pos/reports/inventory_details', $data);
		$this->load->view('templates/bottom');
	}
	
	public function daily_sales_report(){
		if(isset($_GET['date'])){
			$date = $_GET['date'];
		}else{
			$date = date('Y-m-d');
			redirect('pos/reports/daily_sales_report?date='.$date);
		}
		
		
		
		
		$orders = $this->Orders_model->getProductOrderByDate($date);

		// echo "<pre>";
		// // print_r($orders);
		// print_r($items);
		// echo "</pre>";
		// return false;
		$data['date'] = $date;
		$data['orders'] = $orders;
		$data['title'] = 'reports';
		$this->load->view('templates/top', $data);
		$this->load->view('pos/reports/daily_sales_report', $data);
		$this->load->view('templates/bottom');
		
	}
	
	public function print_daily_sales_report(){

		if(isset($_GET['date'])){
			$date = $_GET['date'];
			$orders = $this->Orders_model->getProductOrderByDate($date);

			$data['date'] = $date;
			$data['orders'] = $orders;
			$this->load->view('pos/reports/print_daily_sales_report', $data);
		}else{
			redirect('pos/reports/daily_sales_report');
		}
	}
	

   
}
