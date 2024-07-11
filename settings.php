<?php 
    
    class DatabaseConfig {
        // Default values for database connection
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "testdb";
        private $conn = "";
    
        // Constructor to initialize with custom values if provided
        public function __construct($servername = null, $username = null, $password = null, $dbname = null) {
            if ($servername !== null) {
                $this->servername = $servername;
            }
            if ($username !== null) {
                $this->username = $username;
            }
            if ($password !== null) {
                $this->password = $password;
            }
            if ($dbname !== null) {
                $this->dbname = $dbname;
            }
        }
    
        // Getter methods
        public function getServername() {
            return $this->servername;
        }
    
        public function getUsername() {
            return $this->username;
        }
    
        public function getPassword() {
            return $this->password;
        }
    
        public function getDBName() {
            return $this->dbname;
        }

        function getDBConnection(){
        
            $txtServerName = $this->getServername();
            $txtUserName = $this->getUsername();
            $txtPassword = $this->getPassword();
            $txtDbName = $this->getDBName();
        
            // Create connection
            $conn = new mysqli($txtServerName, $txtUserName, $txtPassword, $txtDbName);
        
            return $conn;
        }

    }

?>