<?php 
    include('backend.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic CRUD with PHP and Bootstrap</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Basic CRUD Application</h2>
    <div class="mb-4">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <input type="hidden" name="id" id="id"  value="0">
        <input type="hidden" name="action" id="action"  value="save">
        <input type="hidden" name="rowNo" id="rowNo"  value="0">
        <button type="submit" class="btn btn-primary save-btn" name="save">Save</button>
    </div>

    <!-- Displaying Records -->
    <table class="table" id="userTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Users";
            $result = $conn->query($sql);
            if(mysqli_num_rows($result) > 0){

            while ($record = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$record['name']}</td>";
                echo "<td>{$record['email']}</td>";
                echo "<td>
                        <button class='btn btn-sm btn-warning edit-btn' data-id='{$record['id']}'>Edit</button>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='{$record['id']}'>delete</button>
                    </td>";
                echo "</tr>";
            }  
        }   
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function(){

         $('.save-btn').click(function(){

            var id = $('#id').val();
            var name = $('#name').val();
            var email = $('#email').val();
            var action = $('#action').val();
            var rowNo = $('#rowNo').val();

            if(name.length > 0 && email.length > 0){
                $.ajax({
                    type: 'POST',
                    url: 'backend.php',
                    data: 
                    { 
                        id: id,
                        name: name,
                        email: email,
                        action: action,
                        rowNo: rowNo
                    },
                    success: function(response){
                        console.log(response)
                        if(response === '-1'){
                            alert("Name or Email already Exist");
                            return;
                        }
                        if(response == 0){
                            alert("Something went wrong saving your data.")
                        }else{
                            var data = JSON.parse(response);
                            if (rowNo == 0) {
                                // Add a new row to the table with the saved data
                                $('#userTable tbody').append(
                                    `<tr>
                                        <td>${data.name}</td>
                                        <td>${data.email}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-btn" data-id="${data.id}">Edit</button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">Delete</button>
                                        </td>
                                    </tr>`
                                );
                            } else if (rowNo > 0) {
                                var rowToUpdate = $('#userTable tbody tr').eq(rowNo - 1);
                                rowToUpdate.find('td:eq(0)').text(data.name);
                                rowToUpdate.find('td:eq(1)').text(data.email);
                            }
                            // Clear form fields after saving or updating
                            $('#name').val('');
                            $('#email').val('');
                            $('#id').val('0');
                            $('#action').val('save');
                            $('#rowNo').val('0');
                            $('#btnSave').text('Save');
                        }
                    },
                    error: function(xhr, status, error){
                        console.error(xhr.responseText);
                    }
                });
              }else{
                alert("Kindly fill up Name and Email");
            }
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var action = "edit";
            // Get the row number of the clicked row
            var rowNo = $(this).closest('tr').index() + 1;
            $.ajax({
                type: 'POST',
                url: 'backend.php',
                data: { id: id, action: action },
                success: function(response){

                    var data = JSON.parse(response);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#rowNo').val(rowNo);
                    $('#id').val(id);
                    $('#action').val('save');
                    $('#btnSave').text('Update');
                },
                error: function(xhr, status, error){
                    // Handle error here (if needed)
                    console.error(xhr.responseText);
                }
            });
        });


        // Function to handle delete button click
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var action = "delete";
            var row = $(this).closest('tr'); // Find the closest <tr> element

            var confirmation = confirm("Are you sure you want to delete this record?");
            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: 'backend.php',
                    data: 
                    { 
                        id: id,
                        action: action
                    },
                    success: function(response){
                        console.log(response)
                        if (response === "true") {
                            row.remove();
                            console.log("Record with ID: " + id + " deleted successfully.");
                        } else {
                            console.log("Failed to delete record with ID: " + id);
                        }
                    },
                    error: function(xhr, status, error){
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
   </script>
</body>
</html>

