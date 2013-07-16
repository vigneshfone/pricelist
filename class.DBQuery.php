<?php
require 'constants.php';
class DBQuery
{
    private $DBconnection = NULL;
    private $result = false;

    function __construct($host, $user, $password,$database)
    {
        $this->DBconnection = mysqli_connect($host, $user, $password,$database);
        if(!is_object($this->DBconnection))
        {
		//echo"false";

            return false;
        }
        else
        {
        //echo"true";
        
    return true;
        }
    }
   public function lastID(){
	//$this->DBconnection;
   }
    public function error() //Get last error
    {
        return (mysqli_error($this->DBconnection));
    }
        
    public function errorno() //Get error number
    {
        return mysql_errno($this->DBconnection);
    }
	        
    public function query($query = '') //Execute the sql query
    {
        $this->result = @mysqli_query($this->DBconnection, $query );
        return $this->result;
    }
        
    public function numRows($result = null) //Return number of rows in selected table
    {
        if(!is_object($result)) $result = $this->result;
        return (@mysqli_num_rows($result));
    }
	
    public function fetchAssoc($result=null)
    {
        if(!is_object($result)) $result = $this->result;
        return (@mysqli_fetch_assoc($result));
    }
} // End class DBQuery
?>
