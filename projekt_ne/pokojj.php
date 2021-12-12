<?php

require_once ("include/db_connect.php");

$state = "OK";

$roomId = filter_input(INPUT_GET, "room_id", FILTER_VALIDATE_INT);
$employeeId = filter_input(INPUT_GET, "employee_id", FILTER_VALIDATE_INT);


if ($roomId === null) {
    http_response_code(400); //bad request
    $state = "BadRequest";
} else {

    $query = "SELECT * FROM room WHERE room_id=:roomId";
    $query2 = "SELECT * FROM employee WHERE room=:roomId";
    $query3 = "SELECT * FROM `key` WHERE room=:roomId";
    // $query4="SELECT room AS kpok FROM `key` AS k INNER JOIN employee AS e ON(k.employee=e.employee_id) WHERE kpok=:roomId";
    $query4="SELECT * FROM `key` AS k INNER JOIN employee AS e ON(k.employee=e.employee_id) WHERE k.room=:roomId";

    
    $pdo = DB::connect();
    
    $stmt = $pdo->prepare($query);
    $stmt2 = $pdo->prepare($query2);
    $stmt3 = $pdo->prepare($query3);
    $stmt4 = $pdo->prepare($query4);

    $stmt->execute(["roomId" => $roomId]);
    $stmt2->execute(["roomId" => $roomId]);
    $stmt3->execute(["roomId" => $roomId]);
    $stmt4->execute(["roomId" => $roomId]);


    

    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        $state = "NotFound";
    } else {
        $room = $stmt->fetch();
    }
}


?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <title> Místnost  číslo <?php echo$room->no ?></title>
</head>
<body class="container">
    
<?php
if ($state === "OK") {
    $prum=0;
    $pom=0;
    echo"<h1>Místnost č.: " . $room->no . "</h1>";
    echo"<dl class='dl-horizontal'>";
    echo"<dt>Číslo: </dt><dd>". ($room->no?: "&mdash;")."</dd>";
    echo"<dt>Název: </dt><dd>". ($room->name?: "&mdash;") ."</dd>";
    echo"<dt>Telefon:</dt><dd>". ($room->phone?: "&mdash;")."</dd>";
    echo"<dt>Lidé:</dt><dd>";
    while ($row = $stmt2->fetch()) {
        $pom++;
        $prum+=$row->wage;
        $jm=$row->name;
        $cut=substr($jm,0,1);
        echo"<a href='osoba.php?employee_id={$row->employee_id}'>{$row->surname} {$cut}.</a></br>";
        
     };
     if($pom==0){
        echo'&mdash;';

     }
    echo"</dd>";
    echo"<dt>AVG Mzda:</dt><dd>";
    if($pom==0){
        echo'&mdash;';
    }
    else{
        echo $prum/$pom;
    }
    echo"</dd>";
    echo"<dt>Klíče:</dt><dd>";
    
    while ($row = $stmt4->fetch()) {
        $jmn=$row->name;
        $cutt=substr($jmn,0,1);
        echo"<a href='osoba.php?employee_id={$row->employee}'>{$row->surname} {$cutt}.</a></br>";
    };

    echo"</dd>";
    echo"</dl>";
    echo"<a href='room.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam místností</a>";
    echo"<a href='index.php'><span class='glyphicon glyphicon-home'></span></a>";

} elseif ($state === "NotFound") {
    echo "<h1>Místnost nenalezena</h1>";
} elseif ($state === "BadRequest") {
    echo "<h1>Chybný požadavek</h1>";
}
?>
</body>
</html>
