<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Giay phep lai xe</title>
    <style>
        .table {
            border-collapse: collapse !important;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd !important;
        }
        table, td, th {
            border: 1px solid green;
        }
        th {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, fullname, license_number, publish_date, license_type FROM customer";
$where = " WHERE 1 = 1";
if(!empty ($_POST)){
    if(!empty ($_POST['fullname'])){
        $where .= " AND fullname like '%" . mysql_real_escape_string($_POST['fullname']) . "%'";
     }
    if(!empty ($_POST['license_number'])){
        $where .= " AND license_number like '%" . mysql_real_escape_string($_POST['license_number']) . "%'";
    }
    if(!empty ($_POST['license_type'])){
        $where .= " AND license_type = '" . mysql_real_escape_string($_POST['license_type']) . "'";
    }
}
$result = mysqli_query($conn, $sql . $where);
?>
<br/>
<form action="license.php" method="post">
    <table class="table table-bordered" border="0" width="500px" cellpadding="5">
        <tr>
            <td>Ho ten</td>
            <td colspan="3"><input type="text" name="fullname" value="<?php echo $_POST['fullname']; ?>"></td>
        </tr>
        <tr>
            <td>So gplx</td>
            <td><input type="text" name="license_number" value="<?php echo $_POST['license_number']; ?>"><br></td>
            <td>hang gplx</td>
            <td><select name="license_type">
                    <option value="">Tat ca</option>
                    <?php
                    $arr = array("A1","A2","B1","B2","B3");
                    foreach ($arr as &$value) {
                        $selected = $_POST['license_type'] && $_POST['license_type'] == $value ? "selected" : "";
                        echo sprintf("<option value='%s' %s>%s</option>", $value, $selected, $value);
                    }?>

            </select></td>
        </tr>
    </table>
    <input type="submit">
</form>
<br/><br/>
<div style="float:right">Tong cong <?php echo mysqli_num_rows($result);?> giay phep</div>
<div>Danh sach ca nhan co GPLX</div>
<table class="table table-bordered" border="1" width="100%" cellpadding="3">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ho va ten</th>
            <th>So giay phep</th>
            <th>Ngay cap</th>
            <th>Hang</th>
        </tr>
    </thead>
    <tbody>
<?php
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["fullname"]. "</td><td>" . $row["license_number"]. "</td><td>" . $row["publish_date"]."</td><td>" . $row["license_type"]."</td>";
    }
} else {
    echo "0 results";
}
?></tbody>
</table>
<?php
mysqli_close($conn);
?>
</body>
</html>