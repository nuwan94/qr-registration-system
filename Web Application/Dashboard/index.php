<?php
include_once('db.php');
include_once('header.php');
?>
<div class="container">
    <div class="row logo-bar">
        <div class="col m4 s12">
            <h1 class="brand-logo center"><img src="img/logo.png" width="300" /></h1>
        </div>
        <div class="col m8 s12">
            <div class="row">
                <?php
                    $getTeamSQL = "SELECT * FROM team";
                    $teamsResult = mysqli_query($conn, $getTeamSQL);
                    $teamsResulCheck = mysqli_num_rows($teamsResult);
                    if($teamsResulCheck>0){
                        while($row = mysqli_fetch_array($teamsResult)){
                            echo('<div class="col s2 btn btn team-b purple darken-2 waves-effect waves-light" onclick="showTeam('.$row['tid'].')">'.$row['tname'].'</div>');
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="result" class="result">
    <?php
        for ($x = 1; $x <= 15; $x++) {
            $sql = "SELECT * FROM users,team WHERE users.tid='".$x."' AND users.tid=team.tid";
            $result = mysqli_query($conn, $sql);
            $resulCheck = mysqli_num_rows($result);
        
        if($resulCheck>0){

        echo('<div class="row team-d" style="display:none" id="team'.$x.'"><form action="update.php" method="post" class="col s12"><div class="row">');
            
            $i=1;
            while($row = mysqli_fetch_array($result)){
            
                if($i==1){
                    echo('<input type="hidden" id="tid" name="tid" value="'.$row['tid'].'">');
                    echo('<div class="row"><input autocomplete="off" type="text" id="tn" name="tn" class="teamName center col s4 push-s1" value="'.$row['tname'].'"><div class="input-field col s4 push-s3"><input autocomplete="off" type="text" id="tu" name="tu" class="teamName center UniversityAutocomplete" value="'.$row['tuni'].'"></div></div>');
                }
                $ldr = ($row['uid'] == $row['tlid'])?'checked':'';
                echo('<div class="col s3 user-card">');
                echo('<label><input name="tl" type="radio" '.$ldr.' value="'.$row['uid'].'"/><span></span></label>');
                echo('<img width="100" class="center" src="img/users/'.$row['uid'].'.jpg" />');
                    $xsmall = "";
                    $small = "";
                    $medium = "";
                    $large = "";
                    $xlarge = "";
                    $xxlarge = "";
                    $emptyT = "";
                switch($row['uts']){
                    case "XS":
                        $xsmall = "selected";
                        break;
                    case "S":
                        $small = "selected";
                        break;
                    case "M":
                        $medium = "selected";
                        break;
                    case "L":
                        $large = "selected";
                        break;
                    case "XL":
                        $xlarge = "selected";
                        break;
                    case "XXL":
                        $xxlarge = "selected";
                        break;
                    default:
                        $emptyT = "selected";
                }
                echo('<div class="input-field col s4">
                    <select name="m'.$i.'ts">
                        <option '.$emptyT.' disabled value="">-</option>
                        <option '.$xsmall.' value="XS">XS</option>
                        <option '.$small.' value="S">S</option>
                        <option '.$medium.' value="M">M</option>
                        <option '.$large.' value="L">L</option>
                        <option '.$xlarge.' value="XL">XL</option>
                        <option '.$xxlarge.' value="XXL">XXL</option>
                    </select>
                </div>');
                
                    $veg = "";
                    $nveg = "";
                    $emptyF = "";
                switch($row['ufp']){
                    case 'V':
                        $veg = "selected";
                        break;
                    case 'N':
                        $nveg = "selected";
                        break;
                    default:
                        $emptyF = "selected";
                }
                echo('<div class="input-field col s8">
                    <select name="m'.$i.'fp">
                        <option '.$emptyF.' disabled value="">-</option>
                        <option '.$nveg.' value="N">Non-Veg</option>
                        <option '.$veg.' value="V">Veg</option>
                    </select>
                </div>');


                echo('<input autocomplete="off" id="m'.$i.'n" name="m'.$i.'n" type="text" placeholder="Member '.$i.' - Name" class="" value="'.$row['uname'].'">');
                echo('<input autocomplete="off" id="m'.$i.'dn" name="m'.$i.'dn" type="text" placeholder="Member '.$i.' - Display Name" class="" value="'.$row['udname'].'">');
                echo('<input autocomplete="off" id="m'.$i.'e" name="m'.$i.'e" type="text" placeholder="Member '.$i.' - Email" class="" value="'.$row['umail'].'">');
                
                echo(createSwitch("Register",$i."r","check_box",$row['ureg']));
                echo(createSwitch("Lunch",$i."l","local_dining",$row['ulun']));
                echo(createSwitch("Dinner",$i."d","local_dining",$row['udin']));
                echo(createSwitch("Breakfast",$i."b","local_dining",$row['ubrk']));
                echo('</div>');
                $i++;
            }
                echo('</div><input class="btn purple darken-2 col s12" type="submit" value="Update"></form></div>');
                // echo('</div></form></div>');
            }
            }
            
            function createSwitch($label,$id,$icon,$bool){
                $b = ($bool)?'checked':'unchecked';
                return '<p><label><input id="m'.$id.'" name="m'.$id.'" type="checkbox" '.$b.' value="'.$bool.'"><span>'.$label.'</span></label></p>';
            }
            ?>
        </div>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large red modal-trigger" href="#modal1">
                <i class="large material-icons">show_chart</i>
            </a>
        </div>
        <form action="reset.php" onsubmit="return checkAdmin()" method="post" class="">
            <input type="submit" class="btn btn-reset btn-large red" value="RESET"/>
        </div>
        <a href="log.php" target="_blank" onclick="return checkAdmin()"  class="btn btn-log btn-large black"> <i class="material-icons">list</i></a>
    </div>

    <div id="modal1" class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class=" active" href="#tshirtStat"><i class="material-icons">style</i> T-Shirts</a></li>
                    <li class="tab col s3"><a class="" href="#foodStat"><i class="material-icons">whatshot</i> Foods</a></li>
                    <!-- <li class="tab col s3"><a class="white-text" href="#errorStat">ToBefilled</a></li> -->
                </ul>
            </div>
            <div id="tshirtStat" class="col s12">
                <?php 
                    $getTshirtCount = "SELECT uts,COUNT(*) AS NUM FROM users WHERE uts!=''  GROUP BY uts 
                                ORDER BY CASE uts
                                    WHEN 'XS' THEN 1
                                    WHEN 'S' THEN 2
                                    WHEN 'M' THEN 3
                                    WHEN 'L' THEN 4
                                    WHEN 'XL' THEN 5
                                    WHEN 'XXL' THEN 6
                                    ELSE 0
                                END";
                    $getTshirtResult = mysqli_query($conn, $getTshirtCount);
                    $tshirttResulCheck = mysqli_num_rows($getTshirtResult);
                    if($tshirttResulCheck>0){
                        echo('<table class=" striped highlight"><tr><th>T-Shirt Size</th><th>Count</th></tr>');
                        while($row = mysqli_fetch_array($getTshirtResult)){
                            echo('<tr><td>'.$row[0].'</td><td>'. $row[1] .'</td></tr>');
                        }
                        echo('</table>');
                    }
                ?>
            </div>
            <div id="foodStat" class="col s12">
                <?php 
                    $getFoodCount = "SELECT ufp,COUNT(*) AS NUM FROM users WHERE ufp!='' GROUP BY ufp";
                    $getFoodResult = mysqli_query($conn, $getFoodCount);
                    $getFoodResultCheck = mysqli_num_rows($getFoodResult);
                    if($getFoodResultCheck>0){
                        echo('<table class="striped highlight"><tr><th>Food Pref</th><th>Count</th></tr>');
                        while($row = mysqli_fetch_array($getFoodResult)){
                            $row[0] = ($row[0]=='N')?'Non-Veg':'Veg';
                            echo('<tr><td>'.$row[0].'</td><td>'. $row[1] .'</td></tr>');
                        }
                        echo('</table>');
                    }
                ?>
            </div>
        </div>
        
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat"><i class="material-icons">close</i></a>
    </div>
  </div>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/plugins.js"></script>
    <script>
    $('.tabs').tabs();
    $('.modal').modal();
    $('select').formSelect();
    $('.fixed-action-btn').floatingActionButton();
    $('.UniversityAutocomplete').autocomplete({
        data: {
        "University of Kelaniya":null,
        "University of Moratuwa":null,
        "University of Colombo School of Computing (UCSC)":null,
        "University of Colombo":null,
        "University of Peradeniya":null,
        "University of Ruhuna":null,
        "University of Jaffna":null,
        "University of Sri Jayewardenepura":null,
        "Rajarata University":null,
        "Sabaragamuwa University":null,
        "Uva Wellassa University":null,
        "South Eastern University":null,
        "Eastern University":null,
        "Wayamba University":null,
        "Open University":null,
        "General Sir John Kotelawala Defence University":null,
        "Sri Lanka Institute of Information Technology (SLIIT)":null,
        "National School of Business Management (NSBM)":null,
        "Informatics Institute of Technology (IIT)":null
        },
    });
    function showTeam(id) {
        var teams = document.getElementsByClassName('team-d')
        for (var i = 0; i < teams.length; i++) {
            teams[i].style.display = "none";
        }
        $("#team" + id).show();
    }
    function checkAdmin(){
    	var t = window.prompt("Password","");
    	if(t==null){return false;}
    	if(t == "RHT18"){
    		return true;
    	}else{
    		alert("Wrong Password");
    		return false;
    	}
    }
    </script>