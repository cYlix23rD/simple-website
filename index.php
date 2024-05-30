<?php
require_once __DIR__ . '/lib/perpage.php';
require_once __DIR__ . '/lib/DataSource.php';
$database = new DataSource();

$name = "";
$code = "";

$queryCondition = "";
if (! empty($_POST["search"])) {
    foreach ($_POST["search"] as $k => $v) {
        if (! empty($v)) {

            $queryCases = array(
                "name",
                "code"
            );
            if (in_array($k, $queryCases)) {
                if (! empty($queryCondition)) {
                    $queryCondition .= " AND ";
                } else {
                    $queryCondition .= " WHERE ";
                }
            }
            switch ($k) {
                case "name":
                    $name = $v;
                    $queryCondition .= "name LIKE '" . $v . "%'";
                    break;
                case "code":
                    $code = $v;
                    $queryCondition .= "code LIKE '" . $v . "%'";
                    break;
            }
        }
    }
}
$orderby = " ORDER BY id desc";
$sql = "SELECT * FROM toy " . $queryCondition;
$href = 'index.php';

$perPage = 3;
$page = 1;
if (isset($_POST['page'])) {
    $page = $_POST['page'];
}
$start = ($page - 1) * $perPage;
if ($start < 0)
    $start = 0;

$query = $sql . $orderby . " limit " . $start . "," . $perPage;
$result = $database->select($query);

if (! empty($result)) {
    $result["perpage"] = showperpage($sql, $perPage, $href);
}
?>
<html>
<head>
<title>BSIT2B-INTEG</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/table.css" />
<link rel="stylesheet" type="text/css" href="css/form.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body{
        display: flex;
        justify-content: center;
        align-items: center;
    }
footer{
    text-align: center;
}
.footer button{
    border: 2px solid orange;
    color: black;
    border-radius: 20px;
}
.footer button:hover{
    background-color: orange;
    color: white;
    border: 2px solid yellowgreen;
    transition: background-color 0.5s;
}
.footer button:active{
    color: black;
}
button, input[type=submit].btnSearch {
    width: 80px;
    font-size: 14px;
    margin: 10px 0px 0px 10px;
}
.btnsearch:hover{
    box-shadow: 2px 2px;
}
.btnReset{
    background-color: yellowgreen;
}
.btnReset:hover{
    box-shadow: 2px 2px;
}
.phppot-container{
    border: 2px solid blue;
    border-radius: 10px;

}
.add a{
    text-align: center;
    border: none;
    color: #000000;
    padding: 5px 5px 5px 5px;
    margin-right: 15px;
    border-radius: 10px;
    background-color: #ffc72c;
    border-color: #ffd98e #ffbe3d #de9300;
    margin-bottom: 15px;
    float: inline-start;
    width: 80px;
}
.add a:hover{
    border: none;
    box-shadow: 2px 2px;
}
.btnReset {
    width: 80px;
    padding: 8px 0px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 25px;
    color: #000000;
    margin-top: 10px;
}

button, input[type=submit].perpage-link {
    width: auto;
    font-size: 14px;
    padding: 5px 10px;
    border: 2px solid #d2d6dd;
    border-radius: 4px;
    margin: 0px 5px;
    background-color: #fff;
    cursor: pointer;
}

.current-page {
    width: auto;
    font-size: 14px;
    padding: 5px 10px;
    border: 2px solid #d2d6dd;
    border-radius: 4px;
    margin: 0px 5px;
    background-color: #efefef;
    cursor: pointer;
}
.ed a{
    text-decoration: none;
    border: none;
    background-color: yellowgreen;
    border-radius: 5px;
    color: black;
    padding: 5px 5px;
  
}
.ed a:hover{
    background-color: orange;
    transition: background-color 0.3s;
    box-shadow: 2px 2px;
}
.page-number {
    font-size: 18px; 
    color: #2f20d1;
    font-weight: bold; 
}
</style>
</head>
<body>
    <div class="phppot-container">
        <h1><i class="bi bi-airplane"></i> Flight Management</h1>
        <div>
            <form name="frmSearch" method="post" action="">
                <div>
                    <p class="inputs">
                        <input type="text" placeholder="Search Airline" name="search[name]" value="<?php echo $name; ?>" />
                        <input type="submit" name="go" class="btnSearch" value="Search"> 
                        <input type="reset" class="btnReset" value="Refresh" onclick="window.location='index.php'">
                    </p>
                </div>
                <div class="add">
                <a class="font-bold float-right" href="add.php" style="text-decoration: none;">Add New</a>
                </div>
                <table class="stripped">
                    <thead>
                        <tr>
                            <th>Airlines</th>
                            <th>Departure <i class="bi bi-geo-alt"></i></th>
                            <th>Arival <i class="bi bi-geo-alt"></i></th>
                            <th>Ticket Price</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (! empty($result)) {
                        foreach ($result as $key => $value) {
                            if (is_numeric($key)) {
                                ?>
                     <tr>
                            <td><?php echo $result[$key]['name']; ?></td>
                            <td><?php echo $result[$key]['code']; ?></td>
                            <td><?php echo $result[$key]['category']; ?></td>
                            <td><?php echo $result[$key]['price']; ?></td>
                            <td><?php echo$result[$key]['stock_count']; ?></td>
                            <td class="ed"><a class="mr-20"
                                href="edit.php?id=<?php echo $result[$key]["id"]; ?>"><i class="bi bi-pencil"></i> Edit</a>
                                <a
                                href="delete.php?action=delete&id=<?php echo $result[$key]["id"]; ?>"> <i class="bi bi-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php
                            }
                        }
                    }
                    if (isset($result["perpage"])) {
                        ?>
                        <tr>
                            <td class="page-number" colspan="6" align=right> <?php echo $result["perpage"]; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
        <footer class="footer">
            <p>&copy; <span id="currentYear"></span> All Rights Reserved</p>
        </footer>
        <script>
           var currentYear = new Date().getFullYear();
           document.getElementById("currentYear").innerHTML = currentYear;
        </script>
    </div>
</body>
</html>