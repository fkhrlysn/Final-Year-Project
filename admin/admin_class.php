<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM admin where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
					return 1;
			}else{
				return 3;
			}
	}
	function login_instructor(){
		
		extract($_POST);		
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM instructor where id_no = '".$id_no."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 1;
		}else{
			return 3;
		}
}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM admin where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_admin(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		
		$chk = $this->db->query("Select * from admin where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO admin set ".$data);
		}else{
			$save = $this->db->query("UPDATE admin set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_admin(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM admin where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM admin where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO admin set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM admin where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE admin set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " category = '$category' ";
		$data .= ", description = '$description' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO category set $data");
			}else{
				$save = $this->db->query("UPDATE category set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_subject(){
		extract($_POST);
		$data = " subject = '$subject' ";
		$data .= ", description = '$description' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO subjects set $data");
			}else{
				$save = $this->db->query("UPDATE subjects set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subjects where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_instructor(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k=> $v){
			if(!empty($v)){
				if($k !='id'){
					if(empty($data))
					$data .= " $k='{$v}' ";
					else
					$data .= ", $k='{$v}' ";
				}
			}
		}
			if(empty($id_no)){
				$i = 1;
				while($i == 1){
					$rand = mt_rand(1,99999999);
					$rand =sprintf("%'08d",$rand);
					$chk = $this->db->query("SELECT * FROM instructor where id_no = '$rand' ")->num_rows;
					if($chk <= 0){
						$data .= ", id_no='$rand' ";
						$i = 0;
					}
				}
			}

		if(empty($id)){
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM instructor where id_no = '$id_no' ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO instructor set $data ");
		}else{
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM instructor where id_no = '$id_no' and id != $id ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("UPDATE instructor set $data where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_instructor(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM instructor where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_schedule(){
		extract($_POST);
		$data = " instructor_id = '$instructor_id' ";
		$data .= ", title = '$title' ";
		$data .= ", schedule_type = '$schedule_type' ";
		$data .= ", description = '$description' ";
		$data .= ", location = '$location' ";
		if(isset($is_repeating)){
			$data .= ", is_repeating = '$is_repeating' ";
			$rdata = array('dow'=>implode(',', $dow),'start'=>$month_from.'-01','end'=>(date('Y-m-d',strtotime($month_to .'-01 +1 month - 1 day '))));
			$data .= ", repeating_data = '".json_encode($rdata)."' ";
		}else{
			$data .= ", is_repeating = 0 ";
			$data .= ", schedule_date = '$schedule_date' ";
		}
		$data .= ", time_from = '$time_from' ";
		$data .= ", time_to = '$time_to' ";

		if(empty($id)){
			$save = $this->db->query("INSERT INTO schedules set ".$data);
		}else{
			$save = $this->db->query("UPDATE schedules set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_schedule(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM schedules where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_schecdule(){
		extract($_POST);
		$data = array();
		$qry = $this->db->query("SELECT * FROM schedules where instructor_id = 0 or instructor_id = $instructor_id");
		while($row=$qry->fetch_assoc()){
			if($row['is_repeating'] == 1){
				$rdata = json_decode($row['repeating_data']);
				foreach($rdata as $k =>$v){
					$row[$k] = $v;
				}
			}
			$data[] = $row;
		}
			return json_encode($data);
	}
	function save_request(){
		extract($_POST);
		$data = " iname = '$iname' ";
		$data .= ", descr = '$descr' ";
		$data .= ", fromdate = '$fromdate' ";
		$data .= ", todate = '$todate' ";
		$data .= ", status = '$status' ";
			if(empty($requestID)){
				$save = $this->db->query("INSERT INTO request set $data");
			}else{
				$save = $this->db->query("UPDATE request set $data where requestID = $requestID");
			}
		if($save)
			return 1;
	}
	function delete_request(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM request where requestID = ".$requestID);
		if($delete){
			return 1;
		}
	}
	
	
}