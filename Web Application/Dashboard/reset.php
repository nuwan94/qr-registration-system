<?php 
    
    include_once('db.php');


    $sql = "UPDATE users SET ureg='0',ulun='0',udin='0',ubrk='0'";
    if ($conn->query($sql) === TRUE) {
    } else {
    }

    $clearLog = "TRUNCATE log";
    if ($conn->query($clearLog) === TRUE) {
    } else {
    }
    
    header('Location: ./');
    mysqli_close($conn);

?>