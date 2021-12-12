<?php
require_once ("include/db_connect.php");
$pdo = DB::connect();

$state = "OK";

$employeeId = filter_input(INPUT_GET, "employee_id", FILTER_VALIDATE_INT);
$roomId = filter_input(INPUT_GET, "room", FILTER_VALIDATE_INT);

if ($employeeId === null) {
    http_response_code(400); //bad request
    $state = "BadRequest";
} else {

    $query = "SELECT * FROM employee  WHERE     employee_id=:employeeId";
    $query2 = "SELECT * FROM room AS r INNER JOIN `key` AS k ON(r.room_id=k.room) WHERE 	employee=:employeeId";
    $query3 = "SELECT * FROM employee AS e INNER JOIN room AS r ON(e.room=r.room_id) WHERE 	employee_id=:employeeId";


    $pdo = DB::connect();
    $stmt = $pdo->prepare($query);
    $stmt2 = $pdo->prepare($query2);
    $stmt3 = $pdo->prepare($query3);


    $stmt->execute(["employeeId" => $employeeId]);
    $stmt2->execute(["employeeId" => $employeeId]);
    $stmt3->execute(["employeeId" => $employeeId]);

    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        $state = "NotFound";
    } else {
        $employee = $stmt->fetch();
    }
}
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
<body class="container">

    <?php
        if ($state === "OK") {
        echo"<h1>Karta osoby:".$employee->name."</h1>";
        echo"<dl class='dl-horizontal'>";
        echo"<dt>Jméno:</dt><dd>".$employee->name."</dd>";
        echo"<dt>Příjmení:</dt><dd>".$employee->surname."</dd>";
        echo"<dt>Pozice:</dt><dd>".$employee->job."</dd>";
        echo"<dt>Mzda:</dt><dd>".$employee->wage."</dd>";
        echo"<dt>Mistnost:</dt><dd>";
        while ($row = $stmt3->fetch()) {
            echo"<a href='pokojj.php?room_id={$row->room}'>{$row->name}</a></br>";
        };
        echo"</dd>";
        echo"<dt>Klíče:</dt><dd>";
        while ($row = $stmt2->fetch()) {
            echo"<a href='pokojj.php?room_id={$row->room}'>{$row->name}</a></br>";
        };
        echo"</dd>";
        echo"</dl>";
        echo "<a href='lidi.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam zaměstnanců</a>     <a href='index.php'><span class='glyphicon glyphicon-home'></span></a>        ";
        }
        elseif ($state === "NotFound") {
            echo "<h1>Osoba nenalezena!</h1>";
        } elseif ($state === "BadRequest") {
            echo "<h1>Chybný požadavek</h1>";
        }
    ?>
</body>
</html>