<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(empty($this->session->userdata('admin_id'))){
			redirect('admin/login');
			return false;
		}else{
			$this->load->model('m_dashboard');
		}		
	}
	public function index(){
		$data = array(
				'title' 		=> 'Dashboard | Sodagara Komputer',
				'template'  	=> 'admin/pages/dashboard',
				'menu_active'	=> 'dashboard',
				'statistics'		=> json_encode(array(
					'total_customers' 	=> $this->m_dashboard->get_all_rows('customers'),
					'total_revenue' 	=> $this->m_dashboard->get_total_revenue(),
					'total_products'	=> $this->m_dashboard->get_all_rows('products'),
					'total_pending_transaction' => 
						$this->m_dashboard->get_all_rows('transactions',array('status' => 'pending')))),
				'data_by_days'	=> json_encode($this->m_dashboard->get_transaction_per('day')),
				'data_by_month'	=> json_encode($this->m_dashboard->get_transaction_per('month')),
				'data_by_years'	=> json_encode($this->m_dashboard->get_transaction_per('year'))
			);
		$this->load_templates($data);
	}
	public function transaction_days(){
		return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'data' => $this->m_dashboard->get_transaction_per('transactions_per_year'))));
	}
	public function load_templates($data){
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/navbar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	public function cetak_laporan($tipe, $date){
	    $this->load->library('pdf');
	    $this->pdf->setPaper('F4', 'potrait');
	    $this->pdf->filename = "laporan-transaksi-per-".$tipe."-".$date."-sodagar-komputer.pdf";

	    $type_transaction = ($tipe == 'hari') ? 'day' : (($tipe == 'bulan') ? 'month':'year');
	    $explode = explode('-',$date);
	    $where = ($tipe == 'hari') ? array('date' => $date) : (($tipe == 'bulan') ? array('month' => $explode[0],'year' => $explode[1]) : array('year' => $date));

	    $detail = $this->m_dashboard->get_detail_transaction($type_transaction,$where);

	    if($tipe == 'hari'){
	    	$where_transaction = array('DATE(t.created_at)' => $date);
	    }else if($tipe == 'bulan'){
	    	$where_transaction = array(
	    		'YEAR(t.created_at)' => $explode[1],
	    		'MONTH(t.created_at)' => $explode[0]
	    	);
	    }else if($tipe == 'tahun'){
	    	$where_transaction = array('YEAR(t.created_at)' => $date);
	    }
	    $where_transaction['t.status'] = 'accepted';
	    $data = array(
	    	'data' 		=> array(
	    		'title'				=> 'Sodagar Komputer',
	    		'tipe_rekap'		=> 'Transaksi Per '.ucfirst($tipe),
	    		'time'				=> $date,
	    		'transaction_count'	=> $detail[0]->transaction_count,
	    		'total_amount'		=> $detail[0]->total_amount
	    	),
	    	'transaksi' => $this->m_transactions->get_join($where_transaction)
	    );
	    $this->pdf->load_view('admin/pages/laporan_transaksi',$data);
	}

}