var totalRegistrations = 58;
var Queue = [];
var notificationSound = new sound("wav/definite.ogg");
var ErrorSound = new sound("wav/error.ogg");

function checkMsg() {
    if (Queue.length != 0) {
        var x = Queue.shift();
        x.s.play();
        $("#message-box").append('<div class="animated ' + x.a + ' message left-align">' + x.m + '</div>');
        $("#message-box").scrollTop($("#message-box").prop("scrollHeight"));
        $('#queue img:first-child').remove();
    }
}

$(function () {

    setInterval("checkMsg()", 3000);
    var socket = io();

    socket.on("RegCount", function (num) {
        if (num || num == 0)
            $('#numofReg').html(num + " / " + totalRegistrations);
    });

    socket.on("LunCount", function (num) {
        if (num || num == 0)
            $('#numofLun').html(num + " / " + totalRegistrations);
    });

    socket.on("DinCount", function (num) {
        if (num || num == 0)
            $('#numofDin').html(num + " / " + totalRegistrations);
    });

    socket.on("BrkCount", function (num) {
        if (num || num == 0)
            $('#numofBrk').html(num + " / " + totalRegistrations);
    });

    socket.on("error", function (str) {
        Queue.push({
            a: 'flash',
            m: '<img class="profile animated slideInUp" src="img/icons/error.png" />' +
                '<h3>Invalid QR</h3>ERROR CODE : ' + str,
            s: ErrorSound
        });
        $('#queue').append('<img class="icon-row rounded animated slideInLeft" src="img/icons/error.png" />');

    });

    socket.on('Response', function (user, team, tag) {

        var h3Dname = '<h3>' + user[0].udname + '</h3>';
        var teamBold = '<b>' + team[0].tname + '</b>';

        var reg = (user[0].ureg == 1) ? "success" : "error";
        var checkedRegMsg = ' <img class="icon-reg" src="img/icons/' + reg + '.png"/>';
        var checkedFp = (user[0].ufp == 'N') ? "NonVeg" : "Veg";

        var lunchIconPath = '<img class="icon-row" src="img/icons/spaghetti.png" >';
        var dinnerIconPath = '<img class="icon-row" src="img/icons/service.png" >';
        var breakfastIconPath = '<img class="icon-row" src="img/icons/burger.png" >';

        var lunchIcon = (user[0].ulun == 1) ? lunchIconPath : "";
        var dinnerIcon = (user[0].udin == 1) ? dinnerIconPath : "";
        var breakfastIcon = (user[0].ubrk == 1) ? breakfastIconPath : "";

        var message = '<img class="profile animated slideInUp" src="img/users/' + user[0].uid + '.jpg" />';
        var animation = 'fadeIn';
        var sound = notificationSound;

        switch (tag) {

            case 'c':
                animation = 'fadeIn';
                message += h3Dname;
                message += teamBold;
                message += team[0].tuni + '</br>';
                message += checkedRegMsg;
                if (team[0].tlid == user[0].uid)
                    message += '<img class="icon-row" src="img/icons/star.png"/>';
                message += (lunchIcon + dinnerIcon + breakfastIcon);
                message += '<div class="tszie">' + user[0].uts + '</div>';
                message += '<div class="fpref shifted ' + checkedFp + '">' + user[0].ufp + '</div>';
                break;

            case 'r':
                animation = "zoomIn";
                message += '<h3> Hello! ' + user[0].udname + '</h3>';
                message += teamBold;
                message += team[0].tuni + '</br>';
                message += '<h4>Welcome to RealHack 2018.</h4> <img class="icon-reg" src="img/icons/success.png"/>';
                message += '<div class="tszie">' + user[0].uts + '</div>';
                break;

            case 'l':
                message += '<h3>' + user[0].udname + '</h3>';
                message += teamBold;
                message += '<h4>Enjoy your lunch.</h4>';
                message += lunchIconPath;
                message += checkedRegMsg;
                message += '<div class="fpref ' + checkedFp + '">' + user[0].ufp + '</div>';
                break;

            case 'd':
                message += '<h3>' + user[0].udname + '</h3>';
                message += teamBold;
                message += '<h4>Enjoy your dinner.</h4>';
                message += dinnerIconPath;
                message += checkedRegMsg;
                message += '<div class="fpref ' + checkedFp + '">' + user[0].ufp + '</div>';
                break;

            case 'b':
                message += '<h3>' + user[0].udname + '</h3>';
                message += teamBold;
                message += '<h4>Enjoy your breakfast.</h4>';
                message += breakfastIconPath;
                message += checkedRegMsg;
                message += '<div class="fpref ' + checkedFp + '">' + user[0].ufp + '</div>';
                break;

            case 'nr':
                sound = ErrorSound;
                animation = 'flash';
                message += h3Dname;
                message += teamBold;
                message += checkedRegMsg;
                message += '<h4><img class="icon-msg" src="img/icons/warning.png"/> Not registerd.</h4>'
                break;

            case 're':
                sound = ErrorSound;
                animation = 'flash';
                message += h3Dname;
                message += teamBold;
                message += checkedRegMsg;
                message += '<h4><img class="icon-msg" src="img/icons/warning.png"/> Already Registered.</h4>'
                break;

            case 'le':
            case 'de':
            case 'be':
                sound = ErrorSound;
                animation = 'flash';
                message += h3Dname;
                message += teamBold;
                message += checkedRegMsg;
                message += '<h4><img class="icon-msg" src="img/icons/warning.png"/> Already had this meal.</h4>'
                break;
        }
        var msg = {
            a: animation,
            m: message,
            s: sound
        };
        Queue.push(msg);
        // $('#queue').append('<i class="animated slideInLeft medium material-icons">directions_walk</i>');
        $('#queue').append('<img class="icon-row rounded animated slideInLeft" src="img/users/' + user[0].uid + '.jpg" />');
    });
});
