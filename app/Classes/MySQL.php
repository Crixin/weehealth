<?php

namespace App\Classes;

class MySQL {

    protected $connection;
	protected $result;
	
	public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = 'abc@123', $dbname = 'speed', $charset = 'utf8') {
        $this->connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        
		if (!$this->connection)
			die('Não foi possível conectar: ' . mysql_error());
		
        $this->connection->set_charset($charset);
    }
    

    public function select($_query) {
        $rows = $this->connection->query($_query);

        if ($rows->num_rows > 0) {
            $this->result = $rows;
        } else {
            $this->result = null;
        }

        return $this->result;
    }


    public function closeConnection() {
        $this->connection->close();
    }


}