<!-- https://stackoverflow.com/questions/16705530/sorting-html-table-with-a-href-and-php-from-sql-database -->
<!-- https://www.webslesson.info/2016/08/ajax-jquery-column-sort-with-php-mysql.html -->
<?php
require_once ("include/db_connect.php");
$pdo = DB::connect();


if(isset($_GET['order'])){
    $order=$_GET['order'];
}else{
    $order='name';
}
if(isset($_GET['sort'])){
    $sort=$_GET['sort'];
}else{
    $sort='ASC';
}
$stmt = $pdo->query("SELECT * FROM employee ORDER BY $order $sort");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seznam lidí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body><div class="container">


<?php 
$query = 'SELECT * FROM room WHERE room_id=?';
$stmt2 = $pdo->prepare($query);

$sort=='DESC'? $sort='ASC':$sort='DESC';

echo "<h1>Seznam zaměstnanců</h1>";
if($stmt->rowCount()==0){
    echo "databaze neobsahuje zadna data";
}
else{
    echo "<table class='table table-straped'>";
    echo "<thead><tr>
    <a href='index.php'><span class='glyphicon glyphicon-home'></span></a>
    <th><a href= '?order=name&&sort=$sort'>Jmeno</a></th>
    <th><a href= '?order=name&&sort=$sort'>Místnost</a></th>
    <th><a href= '?order=name&&sort=$sort'>Telefon</a></th>
    <th><a href= '?order=job&&sort=$sort'>Pozice</a></th>
    </tr></thead>";

    echo "<tbody>";
    while($row=$stmt->fetch()){
        $stmt2->execute([$row->room]);
        $room = $stmt2->fetch();
        echo "<tr>";
        echo "<td><a href='osoba.php?employee_id={$row->employee_id}'>{$row->name} {$row->surname}</a></td>";
        echo "<td>{$room->name}</td>";
        echo "<td>{$room->phone}</td>";
        echo "<td>{$row->job}</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}
?>
</body>
</html>