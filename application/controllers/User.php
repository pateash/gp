<?php
/*
 * User.php
 *
 * Created on Fri Jan 06 2017 02:10:22 GMT+0530 (IST)
 * Copyright (c) 2017 by Arvind Dhakad. All Rights Reserved.
 *
 * @author Arvind Dhakad
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public $header_data=[];
	private $loggedIn = false;
	private $userPermissions = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('usermodel');
		$this->load->helper('bookupload');
		$this->load->model('bookmodel');
		$this->categories= [];
		$this->load->database();
		// $this->output->cache(3);
		$books = ($this->bookmodel->fetchCategories());
		foreach ($books as $key => $value) {
			$this->categories[$value['main_category_title']][$value['sub_category_title']][] = $value['subject_title'];
		}
		$this->header_data['categories'] = $this->categories;

		$this->load->library('form_validation');
		if(isset($_SESSION['USER_ID']) && !empty($_SESSION['USER_ID'])){
			$this->loggedIn = true;
		}
		date_default_timezone_set("Asia/Kolkata");
	}
	// public function index()
	// {
	//   if(!$this->loggedIn){
	//     redirect('/user/login',true);
	//   }else{
	//   $this->load->view('site/header',$this->header_data);
	//   $this->load->view('user/dashboard');
	//   $this->load->view('site/footer');
	//   }
	// }

	/**
	 * this function loads dashboard which shows all user information which can be edited.
	 */
	public function dashboard()
	{
		if(!$this->loggedIn)
			redirect('/user/login');
		$user_id= $this->session->userdata("USER_ID");
		$data['user_info'] = $this->usermodel->getUserInfo($user_id);
		$this->load->view('site/header',$this->header_data);
		$this->load->view('user/dashboard2',$data);
		$this->load->view('site/footer');
	}

	private function user_password_hash($password)
	{
		$salt = '*a**r%%v@#$i^^n((d))_+ ';
		$salt1 = strrev(md5($password));
		return (hash('SHA256', ($salt . md5($salt1 . base64_encode($password)) . '==""==""=54')));
	}

/*this method contains information about referer functionality

  	public function test()
	{
		$host=$_SERVER['HTTP_HOST'];
		preg_match('@^(?:http://)?([^/]+)@i',$host, $matches);
		$scrapped_host = $matches[1];   # second string so that we do not confuse with http:// https

		echo "$host  and $scrapped_host <BR>";
		if(isset($_SERVER['HTTP_REFERER'])){
			$referer=$_SERVER['HTTP_REFERER'];
		}
		else exit;

		echo $host." ".$scrapped_host."<BR>";

		preg_match('@^(?:http://)?([^/]+)@i',$referer, $matches);
		$scrapped_referer = $matches[1];   # second string so that we do not confuse with http:// https
		echo $scrapped_host." ".$scrapped_referer."<BR>";
	}*/

	public function login()
	{
		// $this->output->set_output(json_encode(array('dfd' => 'arivi')));
		if ($this->loggedIn)
			redirect('/user/dashboard', 'refresh');
		$data['login_err'] = '';
		$this->data['title'] = 'Login';
		if (isset($_POST['_lgn'])) {
			$data['login_err'] = 'Credentials are not valid';
			$this->form_validation->set_rules('email', 'Email', 'required|max_length[255]|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == TRUE) {
				$userdata = $this->usermodel->login(strtolower($_POST['email']), $this->user_password_hash($_POST['password']));
				if ($userdata) {
					#setting session information
					$this->session->set_userdata($userdata);
					redirect('/user/dashboard');
				} else {
					$this->load->view('site/header', $this->header_data);
					$this->load->view('user/login', $data);
					$this->load->view('site/footer');
				}
			} else {
				$this->load->view('site/header', $this->header_data);
				$this->load->view('user/login', $data);
				$this->load->view('site/footer');
			}
			// redirect('admin','refresh');
		} else {
			$this->load->view('site/header', $this->header_data);
			$this->load->view('user/login', $data);
			$this->load->view('site/footer');
		}
	}
	/**
	 * this function updates information form view user/account_settings
	 */
	public function account_settings(){
		if(!$this->loggedIn)
			redirect('user/login');
		$user_id= $this->session->userdata("USER_ID");
		$data['user_info'] = $this->usermodel->getUserInfo($user_id);

		if(!empty($_POST)){

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('user_phone', 'Phone Number', 'trim|exact_length[10]|numeric',array('exact_length'=>'%s is not valid.','numeric'=>'%s should be numeric'));
			$this->form_validation->set_rules('user_interests', 'Interests', 'trim|max_length[100]',array('max_length'=>'Word Limit Exceed.'));
			$this->form_validation->set_rules('user_twitter_id', 'Twitter Handle', 'trim|max_length[50]',array('max_length'=>'Your Twitter Handle is not valid.'));
			$this->form_validation->set_rules('user_facebook_id', 'Facebook Profile', 'trim|max_length[50]',array('max_length'=>'You Facebook Profile is not valid.'));
			$this->form_validation->set_rules('user_website', 'Url', 'trim|max_length[255]|valid_url', array('max_length'=>'Url is not valid.','valid_url'=>'Url is not valid.'));
			if ($this->form_validation->run()==TRUE)
			{
				$phone = $this->input->post('user_phone');
				$user_interests = $this->input->post('user_interests');
				$user_twitter_id = $this->input->post('user_twitter_id');
				$user_facebook_id = $this->input->post('user_facebook_id');
				$user_website = $this->input->post('user_website');

				$data_= array('user_id'=>$user_id,
					'user_phone'=>$phone,
					'user_website'=>$user_website,
					'user_interests'=>$user_interests,
					'user_twitter_id'=>$user_twitter_id,
					'user_facebook_id'=>$user_facebook_id);

				if(!empty($data['user_info']))  $updated = $this->usermodel->updateAccountSettings($data_, $user_id);
				else $updated= $this->usermodel->saveAccountSettings($data_, $user_id);

				if($updated)
					$this->session->flashdata('account_msg','Account settings has been updated successfully!');
				else
					$this->session->flashdata('account_msg','Sorry a problem occured.');
				redirect("/user/account_settings");

			}else{
				$this->load->view('/site/header');
				$this->load->view('/user/account_settings',$data);
				$this->load->view('/site/footer');
			}
		}
		else
		{

			$this->load->view('site/header');
			$this->load->view('user/account_settings',$data);
			$this->load->view('site/footer');

		}
	}

	public function change_password(){
		if(!empty($_POST)){

			$this->form_validation->set_rules('current_password',"Current Password",'required|trim|min_length[6]|alpha_numeric|callback_check_current_password',array('check_current_password'=>'Current password does not match.'));
			$this->form_validation->set_rules('new_password_1',"New Password",'required|trim|min_length[6]|alpha_numeric',
				array(
					'min_length'=>"%s should be of min length 6 digits ",
					'alpha_numeric'=>"%s could contain only alphabet and numbers ",
				));
			$this->form_validation->set_rules('new_password_2',"Re-type Password",'required|trim|min_length[6]|alpha_numeric|matches[new_password_1]',
				array(
					'min_length'=>"%s should be of min length 6 digits ",
					'alpha_numerica'=>"%s could contain only alphabet and numbers ",
					'match'=>'Password does not match.'
				));
			$user_id=$this->session->userdata("USER_ID");
			if($this->form_validation->run()==TRUE)
			{

				$new_password=$this->input->post('new_password_1');
				$new_hashed_password=$this->user_password_hash($new_password);

				$flag=$this->usermodel->updatePassword($new_hashed_password,$user_id);
				if($flag){
					$this->load->view("/site/header");
					$this->load->view("/user/account_settings");
					$this->load->view("/site/footer");
				}
				else
				{
					$this->load->view("/site/header");
					$this->load->view("/user/account_settings");
					$this->load->view("/site/footer");
				}
			}

			else{

				$this->load->view("/site/header");
				$this->load->view("/user/account_settings");
				$this->load->view("/site/footer");
			}
		}
		else{
			echo "<script> alert('password matches')</script>";
			$this->load->view("/site/header");
			$this->load->view("/user/account_settings");
			$this->load->view("/site/footer");
		}
	}
	public function check_current_password($current_password)
	{
		$current_hashed_password=$this->user_password_hash(trim($current_password));
		$db_password=$this->usermodel->getCurrentPassword($this->session->userdata("USER_ID"));
		$db_password = $db_password['user_password'];

		if ($current_hashed_password==$db_password)
		{

			return TRUE;
		}

		return FALSE;

	}



	public function signup(){
		if(isset($_POST)){
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('fname', 'First Name', 'trim|required|max_length[30]');
			$this->form_validation->set_rules('mname', 'Middle Name', 'trim|max_length[30]');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|max_length[30]');
			$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|alpha_numeric',
				array('required'=>'Password is required','min_length'=>'Password must be of Minimum 6 Digits','alpha_numeric'=>'Password should contain only alphabet and numbers'));
			$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password1]|min_length[6]',array('required'=>'Password Confirmation is required','min_length'=>'Password must be of Minimum 6 Digits','alpha_numeric'=>'Password should contain only alphabet and numbers'));
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email',array('required'=>'Email is required'));
		 
		 		if ($this->form_validation->run() == TRUE){

				$fname = $this->input->post('fname', TRUE);
				$mname = $this->input->post('mname', TRUE);
				$lname = $this->input->post('lname', TRUE);

				$email = $this->input->post('email', TRUE);
				// $contact = $this->input->post('contact_no', TRUE);
				$password1 = $this->input->post('password1', TRUE);
				$password2 = $this->input->post('password2', TRUE);
				if($password1==$password2){
					$password= $this->user_password_hash($password1);
				}

				$data = array(
					'user_name' => ucwords(strtolower($fname.' '.$mname.' '.$lname)),
					'user_email' => strtolower($email),
					'user_password' => $password,
					'user_roles' =>'0',
					'user_verified' => 0,
					'user_created_at' => date('Y-m-d H:i:s')
				);
				$insert_id = $this->usermodel->saveUser($data);
				if($insert_id){
					redirect('user/dashboard', 'refresh');
				}
			}else{
				$this->load->view('site/header',$this->header_data);
				$this->load->view('user/register');
				$this->load->view('site/footer');
			}
		}
		// $this->load->view('old/footer_admin_main');
	}


	public function uploadHandler(){
		if(!$this->loggedIn){
			redirect('/user/login','refresh');
		}
		$config['upload_path']          = FCPATH.'static/images/blog/posts/uploads';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 50000;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);
		// var_dump($_POST);
		if ( ! $this->upload->do_upload('image'))
		{
			$error = array('status'=>0,'msg' => $this->upload->display_errors());
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		else
		{
			$u = $this->upload->data();
			$data = array('status'=>1,'msg'=>array('file_name' => $u['file_name'],'url'=>base_url().$this->config->item('BLOG_MEDIA').$u['file_name']));
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	public function editBlog(){
		// $this->load->view('old/header_admin');
		if(!$this->loggedIn){
			redirect('/user/login','refresh');
		}
		if(isset($_POST) && !empty($_POST)){
			$title = $this->input->post('blog_title', TRUE);
			$alias = $this->input->post('blog_alias', TRUE);
			$tags = $this->input->post('blog_tags', TRUE);
			$zenre = $this->input->post('blog_zenre', TRUE);
			$type = $this->input->post('blog_type', TRUE);
			$original_author = $this->input->post('blog_original_author', TRUE);
			$savenopublish = $this->input->post('savenopublish', TRUE);
			if($savenopublish)
				$savenopublish = 1;
			else $savenopublish = 0;
			$contents = $this->input->post('ckeditor', false);
			$author = $this->session->userdata('USERID');
			$data = array(
				'title' => $title,
				'alias' => $alias,
				'tags' => $tags,
				'content' =>$contents,
				'author' => 1,
				'zenre' => $zenre,
				'original_author' => $original_author,
				'type' => $type,
				'is_published'=> $savenopublish,
				'is_approved'=>$this->userCan('approve'),
				'is_deleted'=>0,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'views'=>1,
			);
			$insert_id = $this->usermodel->saveBlog($data);
			if($insert_id){
				redirect('/blog/'.$insert_id.'/'.$alias,'refresh');
			}
		}else{


			if($this->uri->segment(3) && $this->uri->segment(4)){
				$blog_data['blog'] = $this->blogmodel->getSingleBlogById($this->uri->segment(3));
				if(url_title($blog_data['blog']['alias']) == $this->uri->segment(4)){
					$this->header_data['meta_title'] = "तूर्यनाद | ".$blog_data['blog']['title'];
					$this->header_data['page_title'] = "तूर्यनाद | ".$blog_data['blog']['title'];
					$this->header_data['description']=strip_tags(substr($blog_data['blog']['content'],0,400));
					$this->load->view('old/header_admin',$this->header_data);
					$this->load->view('old/edit',$blog_data);
					$this->load->view('old/footer_admin',$this->footer_data);
				}else{
					echo $this->uri->segment(4);
				}
			}
		}
		// $this->load->view('old/footer_admin_main');
	}
	public function upload_book()
	{
		if(!$this->loggedIn){
			redirect('/user/login','refresh');
		}
		error_reporting(1);
		$this->header_data['page_title'] = "Home | Grabpustak";
		$this->header_data['meta_title'] = "Home | Grabpustak ";
		$this->header_data['description']="Grabpustak is the online repository for books. Which contains the large variety of children, college books and large dataset of the nobels.";
		$this->footer_data['facebook'] = true;
		$this->footer_data['map'] = true;
		$upload_data['main_categories'] = $this->bookmodel->getMainCategory();
		$this->load->view('site/header',$this->header_data);
		$this->load->view('site/upload_book_form',$upload_data);
		$this->load->view('site/footer',$this->footer_data);
	}



	public function process_book_upload(){
		if(!$this->loggedIn){
			redirect('/user/login','refresh');
		}
		$config['upload_path']= 'static/books_pdf/';
		$config['allowed_types']= 'pdf';
		$config['max_size']= 1000000;

		$title = $this->input->post('title', TRUE);
		$isbn = $this->input->post('isbn', TRUE);
		$author = $this->input->post('author', TRUE);
		$publication_date = $this->input->post('publication_date', TRUE);
		$description = $this->input->post('book_description', false);
		$category = $this->input->post('book_cat_subject', false);
		$type = $this->input->post('book_type', false);


		$alias = url_title($title, 'underscore');

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('pdf_book'))
		{

			$error = array('error' => $this->upload->display_errors());
			// var_dump($error);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
			// $this->load->view('upload_form', $error);
		}
		else
		{
			$data = $this->upload->data();
			// $this->output->set_content_type('application/json')->set_output(json_encode($data));

			$data = array(
				'book_type'=>$type,
				'book_alias'=>$alias,
				'book_title'=>$title,
				'book_author'=>$author,
				'book_isbn' => $isbn,
				'book_short_desc' => $description,
				'book_long_desc' => $description,
				'book_pages'=>$pages,
				'book_category' => $category,
				'book_publisher' => $this->session->userdata('PUBLISHER_ID'),
				'book_image' => 'poster.png',
				'book_created_at'=> date('Y-m-d H:i:s'),
				'paper_or_publication_date'=>date('Y-m-d'),
				'book_pdf'=> $data['full_path'],
				'book_deleted'=> 1,
			);

			$insert_id = $this->bookmodel->saveBook($data);

			// $this->db->trans_begin();

			// $this->db->query('AN SQL QUERY...');
			// $this->db->query('ANOTHER QUERY...');
			// $this->db->query('AND YET ANOTHER QUERY...');
			// if ($this->db->trans_status() === FALSE)
			// {
			//         $this->db->trans_rollback();
			// }
			// else
			// {
			//         $this->db->trans_commit();
			// }


			if(pdf_to_img($data['full_path'],$data['file_path'].'../book_images/'.$alias)){
				$dir = $data['file_path'].'../book_images/'.$alias;
				$pages = count(array_filter(glob("$dir/*.png") ,"is_file"));
				$msg = [];
				if(file_exists("$dir/1.png")){

					$msg['type']= "Book Upload Success";
					$msg['msg'] = 'Book has been uploaded successfully. <a href="/user/list_book">Click Here</a> to see your uploaded books.';

					copy("$dir/1.png", "$dir/$alias.png");


				}else{
					$msg['type']= "Book Upload Failed";
					$msg['msg'] = 'Sorry unable to process book, Please try again after some time.
           <br><a href="/user/list_book">Click Here</a> to see your uploaded books.';

				}
				$this->load->view('site/header',$this->header_data);
				$this->load->view('site/book_upload_msg',$msg);
				$this->load->view('site/footer',$this->footer_data);

				// foreach (array_filter(glob("$dir/*.png") ,"is_file") as $f)
				//  rename ($f, md5(uniqid().$f));
				// $this->load->view('upload_success', $data);
			}
		}

	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('/','refresh');
	}

	public function profile($user_username){
		$user=$this->usermodel->getUserByUserName($user_username);
		$this->load->view('site/header',$this->header_data);
		if($user){
			$user_info=$this->usermodel->getUserInfo($user['user_id']);
			//	var_dump($user_info);
			$data['user_info'] = $user_info;//data from user_information table
			$data['user']=$user;//data from users table
			$this->load->view('user/profile',$data);
		}else{
			$this->load->view("errors/html/error_user_not_found",$user['user_username']);
		}
	
		$this->load->view('site/footer');
	}

}
