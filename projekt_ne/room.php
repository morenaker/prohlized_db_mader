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
$stmt = $pdo->query("SELECT * FROM room ORDER BY $order $sort");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body><div class="container">

<?php 
$sort=='DESC'? $sort='ASC':$sort='DESC';
echo "<h1>Seznam místností</h1>";
if($stmt->rowCount()==0){
    echo "databaze neobsahuje zadna data";
}
else{ 
    echo "<table class='table table-straped'>";
    echo "<thead>
    <a href='index.php'><span class='glyphicon glyphicon-home'></span></a>
    <tr><th><a href= '?order=name&&sort=$sort'>Název</a></th>
    <th><a href= '?order=no&&sort=$sort'>Čislo</a></th>
    <th><a href= '?order=phone&&sort=$sort'>Telefon</a></th>
    </tr></thead>";

    echo "<tbody>";
    while($row=$stmt->fetch()){
        echo "<tr>";
        echo "<td><a href='pokojj.php?room_id={$row->room_id}'>{$row->name}</a></td>";
        echo "<td>{$row->no}</td>";
        echo "<td>" . ($row->phone ?: "&mdash;") . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}
?>
</body>
</html>