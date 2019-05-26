<?php 
    
    include_once('db.php');
    $t = $_POST['tid'];
    $n = $_POST['tn'];
    $u = $_POST['tu'];
    $l = $_POST['tl'];

    $teamUpdate = "UPDATE team SET tname='".$n."',tuni='".$u."',tlid='".$l."' WHERE tid=".$t;

    if ($conn->query($teamUpdate) === TRUE) {
        echo "Record updated successfully ";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    updateAll($conn,$t);

    function updateAll($conn,$t){
        for($x=1;$x<=4;$x++){
            updateText($conn,$t,$x,"m".$x."n",'uname');
            updateText($conn,$t,$x,"m".$x."dn",'udname');
            updateText($conn,$t,$x,"m".$x."e",'umail');
            updateBool($conn,$t,$x,"m".$x."r",'ureg');
            updateText($conn,$t,$x,"m".$x."ts",'uts');
            updateText($conn,$t,$x,"m".$x."fp",'ufp');
            updateBool($conn,$t,$x,"m".$x."l",'ulun');
            updateBool($conn,$t,$x,"m".$x."d",'udin');
            updateBool($conn,$t,$x,"m".$x."b",'ubrk');
        }
    }

    header('Location: ./');

    function updateText($conn,$t,$i,$prop,$field){
        $t = ($t<10)?'0'.$t:$t;
        if(isset($_POST[$prop])==false || $_POST[$prop]=="-") return;
        $updateSql = "UPDATE users SET ".$field."='".$_POST[$prop]."' WHERE uid='RHT".$t."M0".$i."'";
        if ($conn->query($updateSql) === TRUE) {
            // header('Location: ./');
        }else{
            echo "Error : " . $conn->error;
        }
    }

    function updateBool($conn,$t,$i,$prop,$field){
        $bool = (isset($_POST[$prop]))?1:0;
        $t = ($t<10)?'0'.$t:$t;
        $updateSql = "UPDATE users SET ".$field."='".$bool."' WHERE uid='RHT".$t."M0".$i."'";
        if ($conn->query($updateSql) === TRUE) {
            // header('Location: ./');
        }else{
            echo "Error : " . $conn->error;
        }
    }    

    mysqli_close($conn);
?>