<?php 

require("settings.php");

$db = new DatabaseConfig();

$conn = $db->getDBConnection();

if ($conn -> errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

if(isset($_POST) and isset($_POST["action"])){
    $action = $_POST["action"];

    if($action == "save"){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        $where = "";
        if($id != 0){
            $where .= "AND id = $id";
        }
        

        $sql = "SELECT * FROM Users WHERE name = '$name' or email = '$email' $where ";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO Users (name, email) VALUES ('$name', '$email') ";
            $result = $conn->query($sql);
            $id =  $conn->insert_id;
            $row = array(
                'id' => $id,
                'name' => $name,
                'email' => $email
            );
            echo json_encode($row);
        }
        else{
            if($id > 0){
                $sql = "UPDATE Users SET name = '$name', email='$email' WHERE Id = '$id' ";
                $result = $conn->query($sql);
                $row = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'rowNo' => $_POST["rowNo"]
                );
                echo json_encode($row);
            }else{
                echo "-1";
            }
            
        }
        
    }

    if($action == "edit"){
        $id = $_POST['id'];
        $sql = "SELECT * FROM Users where Id = $id";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Encode the fetched data as JSON and echo it
            echo json_encode($row);
        } else {
            // If no record found, return an empty object
            echo json_encode((object)array());
        }
    }
    
    if($action == "delete"){
        $id = $_POST['id'];
        try {
            $sql = "DELETE FROM Users WHERE Id = $id ";
            // echo $sql;   
            $result = $conn->query($sql);
            
            if ($conn->affected_rows > 0) {
                echo "true";
            } else {
                echo "false";
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

}

?>