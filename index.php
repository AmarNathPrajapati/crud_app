<!-- connecting to a database -->

<?php
$insert = false;
$update = false;
$delete = false;
//connection with database
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'mynotes';

//conntection query
$conn = mysqli_connect($hostname, $username, $password, $database);
if ($conn) {
    // echo  'connection successfull';
} else {
    die('due to some error connection was not created, the error may be ' . mysqli_connect_error());
}
?>
<!-- deleting a note -->
<?php
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `mynotes` WHERE `mynotes`.`id` = $sno;";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $delete = true;
    } else {
        die("Something went wrong during deletion " . mysqli_error($conn));
    }
}
?>
<!-- insering and updating data into table -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        //updating table into table
        $sno = $_POST['snoEdit'];
        $title  = $_POST['titleEdit'];
        $desc = $_POST['descriptionEdit'];
        // UPDATE `mynotes` SET `title` = 'learn ', `description` = 'learn more and do smart work' WHERE `mynotes`.`id` = 18;

        $stmt = "UPDATE `mynotes` SET `title` = (?), `description` = (?) where `mynotes`.`id` = $sno ;"; //type the statement
        $sql = mysqli_prepare($conn, $stmt); //prepare the statement
        mysqli_stmt_bind_param($sql, 'ss', $title, $desc); //
        $result = mysqli_stmt_execute($sql);
        if ($result) {
            mysqli_stmt_close($sql);
            $update = true;
        } else {
            die('due to :' . mysqli_error($conn));
        }
    } else if ($_POST['title'] && $_POST['description']) {
        // inserting data into table
        $title  = $_POST['title'];
        $desc = $_POST['description'];
        $stmt = "INSERT INTO `mynotes` (title, description) VALUES (?, ?);"; //type the statement
        $sql = mysqli_prepare($conn, $stmt); //prepare the statement
        mysqli_stmt_bind_param($sql, 'ss', $title, $desc); //
        $result = mysqli_stmt_execute($sql);
        if ($result) {
            mysqli_stmt_close($sql);
            $insert = true;
        } else {
            die('due to :' . mysqli_error($conn));
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- datatable css -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <title>My CRUD APP</title>
</head>

<body>
    <!--Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">Close</span>
                    </button>
                </div>
                <form action="/crud/index.php" method="POST">
                    <div class="modal-body">
                        <!-- hidden input tag -->
                        <input type="hidden" name="snoEdit" id="snoEdit">

                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>

                        <div class="form-group">
                            <label for="desc">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-block mr-auto">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MyNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- success alert after successful insertion -->
    <?php
    if ($insert) {
    ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been inserted successfully
            <button class="btn btn-success" type='button' class='close' aria-hidden='true' data-dismiss='alert' aria-label='Close'> Close
            </button>
        </div>
    <?php
    }
    ?>
    <?php
    if ($update) {
    ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been updated successfully
            <button class="btn btn-success" type='button' class='close' aria-hidden='true' data-dismiss='alert' aria-label='Close'> Close
            </button>
        </div>
    <?php
    }
    ?>
    <?php
    if ($delete) {
        $delete = false;
    ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note has been deleted successfully
            <button class="btn btn-success" type='button' class='close' aria-hidden='true' data-dismiss='alert' aria-label='Close'> Close
            </button>
        </div>
        
    <?php
    }
    ?>

    <!-- main form for insertion -->
    <div class="container m-5">
        <form action="./index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">NOTE Title</label>
                <input name="title" type="text" class="form-control" id="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">NOTE Description</label>
                <textarea name="description" class="form-control" id="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-primary d-grid">Add Note</button>
        </form>
    </div>
    <div class="container mx-5">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Note Title</th>
                    <th scope="col">Note Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- fetching data from database  -->
                <?php
                $sql = "SELECT * FROM `mynotes`;";
                $result = mysqli_query($conn, $sql);
                $sno = 1;
                while ($rows = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $sno ?></th>
                        <td><?php echo $rows['title'] ?></td>
                        <td><?php echo $rows['description'] ?></td>
                        <td>
                            <button type="button" id=" <?php echo $rows['id'] ?> " class="btn btn-warning btn-sm edit"> Edit </button>
                            <button type="button" id=" <?php echo 'd' . $rows['id'] ?> " class="btn btn-danger btn-sm delete"> Delete </button>

                        </td>
                    </tr>
                <?php
                    $sno = $sno + 1;
                }
                ?>
            </tbody>

        </table>
    </div>
    <!-- then Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- jquery script for data table -->
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <!-- script for edit -->
    <script>
        // updating query for delete
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                // console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                // console.log(e.target.id)
                $('#editModal').modal('toggle');
            })
        })
        //deleting query for delete
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                let sno = e.target.id;
                sno = sno.substr(2, );
                if (confirm("Are you really want to delete this notes")) {
                    window.location = `/crud/index.php?delete=${sno}`;
                }

            })
        })
    </script>
</body>

</html>