<?php require_once("Functions.php");  ?>


<?php 

Functions::header_html();

$conn = Functions::connection();

if (
    isset($_POST['submit'])) {
    $insert_header_query ="insert into call_header (it_person,user_name,subject,details,total_hours,total_minutes,status) ";
    $insert_header_query .= "values ('$_POST[it_person]','$_POST[user_name]','$_POST[subject]','$_POST[details]','$_POST[hours]','$_POST[minutes]','$_POST[status]')";


    $insert_header=mysqli_query($conn,$insert_header_query);

    if (!$insert_header) {
        ?>
        <div class="alert alert-danger" role="alert">
            Insert header query has failed
        </div>
        <?php
    }
    else {
        ?>
        <div class="alert alert-success" role="alert">
            Call Header saved!
        </div>
        <?php
    }

}

if (isset($_POST['submit_search'])) {

    $search_query='';

    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    if (validateDate($_POST['submit_search'])) {
        $search_query='select * from call_header where date like "'.$_POST['submit_search'].'%"';
    }
    else {
        $search_query='select * from call_header where
        id="'.$_POST['submit_search'].'" 
        or user_name like "%'.$_POST['submit_search'].'%"';
    }

    $search = mysqli_query($conn,$search_query);

    if (!$search) {
        ?>
        <div class="alert alert-danger" role="alert">
             Search query has failed
        </div>
        <?php
    }

}

$query='select * from call_header order by id desc';

$headers=mysqli_query($conn,$query);

?>

<div class="container">

<h3>New Call Header</h3>

<form action="index.php" method="post">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <label>It person:</label>
                <input type="text" name="it_person" class="from-cntrol">
            </div>
            <div class="col-sm">
                <label>User name:</label>
                <input type="text" name="user_name" class="from-cntrol">
            </div>
            <div class="col-sm">
                <label>Subject:</label>
                <input type="text" name="subject" class="from-cntrol">
            </div>
        </div>

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
                <label>Status:</label>
                <select name="status" class="from-cntrol">
                    <option value="new">New</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <label>Details:</label>
                <input type="details" name="details" class="from-cntrol">
            </div>
            <div class="col-sm"></div>
            <div class="col-sm">
                <input type="submit" name="submit" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>


<h3>Search for Headers</h3>

<p>Search for either id, user name or date (YYYY-MM-DD).</p>

<form action="index.php" method="post">
        <div class="row">
            <div class="col-lg">
                <label>Search:</label>
                <input type="search" name="submit_search">
            </div>
        </div>
</form>

<?php
if (isset($_POST['submit_search'])) {
    echo "<h3>Search Result</h3>";
}
else {
    echo "<h3>Call Headers</h3>";
}
?>

<table class="table">
<tr>
    <th>ID</th>
    <th>Created When</th>
    <th>IT Person</th>
    <th>User Name</th>
    <th>Subject</th>
    <th>Details</th>
    <th>Time Spent</th>
    <th>Status</th>
    <th>Link</th>
</tr>
<?php

$fetch=isset($_POST['submit_search']) ? $search : $headers;

while($row = mysqli_fetch_assoc($fetch)) {

    $all_mins_to_hours= Functions::get_time_spent($row['id'],$row['total_hours'],$row['total_minutes']);

   echo "
   <tr>
   <td> $row[id] </td> 
   <td> $row[date] </td>
   <td> $row[it_person] </td>
   <td> $row[user_name] </td>
   <td> $row[subject] </td>
   <td> $row[details] </td>
   <td> $all_mins_to_hours </td>
   <td> $row[status] </td>
   <td> <a href=call_header.php?id=$row[id]>Check Details</a> </td>
   </tr>";


}
?>


</table>

</div>

<?php
Functions::bottom_html();
?>



