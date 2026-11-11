<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prints extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Prints_model');
		$this->load->model('Settings_model');
    }

    public function index()
    {
		$unprinted = $this->Prints_model->getUnprinted();
		$printed = $this->Prints_model->getPrinted(20);
		
		$data['unprinted'] = $unprinted;
		$data['printed'] = $printed;
		$data['title'] = 'prints';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/print/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function do_print(){
		$unprinted = $this->Prints_model->getUnprinted(1);
		$settings = getSettings();

		
		echo "<center><h3>Printing</h3><img src='".base_url('assets/images/loader_3.gif')."'></center>";
		// $url = "index.php?view=customer&custId=$custId";
		// echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";


		$port = "9100";
		// $host = "192.168.1.54";
		$host = $settings['kitchen_ip'];
		
		$br = "\n";
		$br = "<br>";
		
		foreach($unprinted as $unprint){
			
			$data = json_decode($unprint->prtData);
			// $data = $data[0];
			
			if($unprint->prtType == 1){
				$header = "Stone Haven".$br;
				
				$header .= "Table: ".$unprint->tbName.$br;
				
				$header .= $br."Order details:".$br;
				$total = 0;
				$body = "";
				foreach($data as $item){
					$body .=$item->prodName;
					$body .=" ".$item->ctQty;
					$body .="x ".number_format($item->ctSales,2).$br;
					$total = $total + $item->ctSales;
				}
				$footer = "----------------------------------";
				$footer = "Total:".number_format($total,2);
				
				$html = $header . $body . $footer;
				
				// $html = "<table>";
				// $html .="<tr>";
				// $html .="<td>";
				// $html .="Stone Haven";
				// $html .="<td>";
				// $html .="</tr>";
				// $html .= "</table>";
				
				
				
			}else{
				
			}
			
			
			echo $html;
			echo "<pre>";
			print_r($unprint);
			print_r($data);
			echo "</pre>";
			
		}
	}
}



// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			// $result = socket_connect($socket, $host, $port);
			// if (($socket === false) && ($result === false)) {
				// echo "socket_create() failed, reason: " . socket_strerror(socket_last_error    ()) . "\n";
			// } else {
				// // //echo "OK.\n";
				// foreach($items as $item)
				// {		
					// $html .= "x".$item['qty']." == ". $item['name'] .$nextline;
				// }
				
				// $html .= $mark . $nextline . $Waiter;
				// $html .= $var4;

				// socket_write($socket, $html);
				// socket_close($socket);

				// // $url = "index.php?view=list&sid=$tablenumber";
				// // echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";
				
			// }
			// echo "";