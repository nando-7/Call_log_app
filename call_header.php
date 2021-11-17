<?php require_once("Functions.php");  ?>

<?php 

Functions::header_html();

$conn = Functions::connection();

if (isset($_POST['submit'])) {
    $insert_details_query ="insert into call_details (call_id,details,hours,minutes) ";
    $insert_details_query .= "values ('$_GET[id]','$_POST[details]','$_POST[hours]','$_POST[minutes]')";

    $insert_details=mysqli_query($conn,$insert_details_query);

    if (!$insert_details) {
        ?>
        <div class="alert alert-danger" role="alert">
            Insert details query has failed
        </div>
        <?php
    }
    else {
        ?>
        <div class="alert alert-success" role="alert">
            Call detail saved!
        </div>
        <?php
    }
}

if (isset($_POST['update_status'])) {
    $update_query = 'update call_header set status="'.$_POST['status'].'" where id='.$_GET['id'];

    $update_status=mysqli_query($conn,$update_query);

    if (!$update_status) {
        ?>
        <div class="alert alert-danger" role="alert">
            Update query has failed
        </div>
        <?php
    }
    else {
        ?>
        <div class="alert alert-success" role="alert">
            Status has been updated!
        </div>
        <?php
    }


}

if (isset($_POST['delete'])) {
    $delete_query = 'delete from call_details where id='.$_GET['id'];

    $delte=mysqli_query($conn,$delete_query);

    if (!$delte) {
        ?>
        <div class="alert alert-danger" role="alert">
            Delete query has failed
        </div>
        <?php
    }
    else {
        ?>
        <div class="alert alert-warning" role="alert">
            Call detail deleted!
        </div>
        <?php
    }

}

$query_header='select * from call_header where id='.$_GET['id'];

$header=mysqli_query($conn,$query_header);

$query_details='select * from call_details where call_id='.$_GET['id'];

$details=mysqli_query($conn,$query_details);




?>

<div class="container">

<div class="jumbotron">

    <div class="row">
    <div class="col-6">
        <h3>Call Header</h3>
    </div>
    <div class="col-6">
        <div class="float-right">
            <a href="index.php">Go Back to Inicial Page</a>
        </div>
    </div>
    </div>

    <table class="table">
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>IT Person</th>
        <th>User Name</th>
        <th>Subject</th>
        <th>Details</th>
        <th>Time Spent</th>
        <th>Status</th>
        <th>Update Status</th>
    </tr>
    <?php
    while($row = mysqli_fetch_assoc($header)) {

        $all_mins_to_hours= Functions::get_time_spent($row['id'],$row['total_hours'],$row['total_minutes']);

        $in_progress=$row['status']=='in_progress'?'selected':'';
        $completed=$row['status']=='completed'?'selected':'';

    echo "
    <tr>
    <td> $row[id] </td> 
    <td> $row[date] </td>
    <td> $row[it_person] </td>
    <td> $row[user_name] </td>
    <td> $row[subject] </td>
    <td> $row[details] </td>
    <td> $all_mins_to_hours </td>
    <form action='call_header.php?id=$_GET[id]' method='post'>
    <td> 
        <select name='status' class='from-cntrol'>
            <option value='new'>New</option>
            <option value='in_progress' $in_progress>In Progress</option>
            <option value='completed' $completed>Completed</option>
        </select>
    </td>
    <td>
    
        <button type='submit' name='update_status' value=$row[id] class='btn btn-warning'>Update</button>
    </form>
    </td>
    </tr>";
    }
    ?>
    </table>

</div>

<h3>Enter New Detail</h3>

<?php
    echo "<form action='call_header.php?id=$_GET[id]' method='post'>"
?>
        <div class="row">
            <div class="col-sm">
                <label>Hours:</label>
                <input type="text" name="hours" class="from-cntrol">
            </div>
            <div class="col-sm">
                <label>Minutes:</label>
                <input type="text" name="minutes" class="from-cntrol">
            </div>
            <div class="col-sm">
                <label>Details:</label>
                <input type="details" name="details" class="from-cntrol">
            </div>
            <div class="col-sm">
                <input type="submit" name="submit" class="btn btn-primary">
            </div>
        </div>
</form>

<h3>Details List</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Call ID</th>
    <th>Created When</th>
    <th>Details</th>
    <th>Time Spent</th>
    <th>Delete</th>
</tr>
<?php
while($row = mysqli_fetch_assoc($details))
{
    $hours_to_mins=$row['hours']*60;

    $all_mins_to_hours= Functions::convertToHoursMins($hours_to_mins+$row['minutes']);

   echo "
   <tr>
   <td> $row[id] </td> 
   <td> $row[call_id] </td>
   <td> $row[date] </td>
   <td> $row[details] </td>
   <td> $all_mins_to_hours </td>
   <td>
    <form action='call_header.php?id=$_GET[id]' method='post'>
        <button type='submit' name='delete' value=$row[id] class='btn btn-danger'>Delete</button>
    </form>
   </td>
   </tr>";


}
?>
</table>

</div>

<?php
Functions::bottom_html();
?>



