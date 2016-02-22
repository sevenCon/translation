<?php

//if (!defined('ROOT')) exit('无访问权限');
	
	class db{
		//字段数组
		private $array_name;
		
		//字段值数组
		private $array_value; 
		
		//数据库链接
		private $conn;
		
		//执行返回的记录数
		private $num; 
		
		//执行返回变量
		private $result; 
		
		//执行返回数组	
		private $rows; 
		
		//mysql执行语句
		private $sql; 
		
		public function connect($dbhost='localhost', $dbuser='root', $db_password, $db_name){
			$this->rows = '';
			$this->conn = mysql_connect($dbhost, $dbuser, $db_password) or die('无法连接数据库'.mysql_error());
			
			if($this->conn) {
				mysql_select_db($db_name);
				mysql_query("set names gb2312;");
			}
			return $this->conn;
		}
		
		public function close(){
			mysql_close($this->conn);
		}
		
		public function excute($sql){
			$this->sql = $sql;
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			return $this->result;
		}
		
		public function getRow(){
			if($this->result){
				$this->rows = mysql_fetch_array($this->result);
				return $this->rows;
			}
			return false;
		}
		
		public function getSql(){
		
			return $this->sql;
		}
		
		public function getRows(){
			if($this->result){
				$this->rows = array();
				while($row = mysql_fetch_array($this->result)){
					array_push($this->rows,$row);
				}
				//print($this->sql."<br/>");
				//print_r($this->rows);
				return $this->rows;
			}
			return false;
		}
		
		public function getCount(){
			if($this->result){
				return mysql_num_rows($this->result);
			}
		}
		
		public function fetchRows($table, $where=array(), $orderkey='', $order='ASC'){
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			if($orderkey) $order_sql = "order by $orderkey $order";
			
			$this->sql = "select * from `$table` $where_sql $order_sql;";
			
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			//if(!$this->result) return false;
			$this->rows = array();
			if(mysql_num_rows($this->result)){
				while($row = mysql_fetch_array($this->result)){
					array_push($this->rows,$row);
				}
			}
			
			return $this->rows;
			
		}
		
		public function findRows($table, $where=array(), $orderkey='', $order='ASC'){
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key` LIKE '$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			if($orderkey) $order_sql = "order by $orderkey $order";
			
			$this->sql = "select * from `$table` $where_sql $order_sql;";
			
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			if(!$this->result) return false;
			if(mysql_num_rows($this->result)!=0)
			{
				$this->rows = array();
				if(mysql_num_rows($this->result)){
					while($row = mysql_fetch_array($this->result)){
						array_push($this->rows,$row);
					}
				}
				return $this->rows;
			}
			else return array();	
		}
		
		public function fetchRowsPage($table, $where=array(), $orderkey='', $order='ASC',$limit_from,$limit_to=''){
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			if($orderkey) $order_sql = "order by $orderkey $order";
			
			$this->sql = "select * from `$table` $where_sql $order_sql limit $limit_from,$limit_to;";
			//print($this->sql);
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			//if(!$this->result) return false;
			$this->rows = array();
			if(mysql_num_rows($this->result)){
				while($row = mysql_fetch_array($this->result)){
					array_push($this->rows,$row);
				}
			}
			
			return $this->rows;
			
		}
		
		public function fetchRow($table, $where=array()){
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			
			$this->sql = "select * from `$table` $where_sql;";
			//return $this->sql;
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			if(!$this->result) return false;
			$this->rows = mysql_fetch_array($this->result);
			return mysql_num_rows($this->result)?$this->rows : array();
		}
		
		public function getNum($table, $where=array()){
			if($table == '') return false;
			$where_sql = '';
			//print_r($where);
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			
			$this->sql = "select * from `$table` $where_sql;";
			//print($this->sql);
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			if(!$this->result) return false;
			$this->num = mysql_num_rows($this->result);
			return $this->num;
		}
		
		public function postRow($table,$data){
			if($table == '') return false;
			
			$data_sql = '';
			if(is_array($data) && count($data)){
				foreach($data as $key=>$val){
					$data_sql .= ", `$key`='$val'";
				}
				$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);
			}
			
			$this->sql = "insert into `$table` $data_sql;";
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			return true;
		}
		
		public function updateRow($table,$data,$where){
			if($table == '') return false;
			
			$data_sql = '';
			if(is_array($data) && count($data)){
				foreach($data as $key=>$val){
					$data_sql .= ", `$key`='$val'";
				}
				$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);
			}
			
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			$this->sql = "update `$table` $data_sql $where_sql;";
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			return true;
		}
		
		function deleteRows($table, $where){
			if($table == '') return false;
			
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			$this->sql = "delete from `$table` $where_sql;";
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			return true;
		}
		
		function deleteRow($table, $where){
			if($table == '') return false;
			
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			$this->sql = "delete from `$table` $where_sql limit 1;";
			$this->result = mysql_query($this->sql) or die(mysql_error().$this->sql);
			return true;
		}
		
	}

?>