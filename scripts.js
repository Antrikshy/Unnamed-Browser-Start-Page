$(document).ready(function() {
    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    function startTime() {
        var today = new Date();

        var hour = today.getHours();
        var minute = today.getMinutes();
        var second = today.getSeconds();
        minute = addZero(minute);
        second = addZero(second);
        
        var weekday = new Array(7);
        weekday[0]=  "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";
        var day = weekday[today.getDay()];
        
        var date = today.getDate();
        var dateNumerals = new Array(4);
        dateNumerals[0] = "th";
        dateNumerals[1] = "st";
        dateNumerals[2] = "nd";
        dateNumerals[3] = "rd";
        dateNumerals[4] = "th";
        dateNumerals[5] = "th";
        dateNumerals[6] = "th";
        dateNumerals[7] = "th";
        dateNumerals[8] = "th";
        dateNumerals[9] = "th";
        var todayNumeral = dateNumerals[date % 10];

        var monthNames = new Array(12);
        monthNames[0] = "January";
        monthNames[1] = "February";
        monthNames[2] = "March";
        monthNames[3] = "April";
        monthNames[4] = "May";
        monthNames[5] = "June";
        monthNames[6] = "July";
        monthNames[7] = "August";
        monthNames[8] = "September";
        monthNames[9] = "October";
        monthNames[10] = "November";
        monthNames[11] = "December";
        var month = monthNames[today.getMonth()];
        
        document.getElementById('time').innerHTML = hour + ":" + minute + ":" + second;
        t = setTimeout(function(){
            startTime()
        }, 500);

        document.getElementById('date').innerHTML = "It's " + day + ", the " + date + todayNumeral + " of " + month + ".";
    }

    startTime();

    $.cookie('userName', 'Antriksh Yadav');

    var userName = $.cookie('userName');
    
    if (userName != undefined)
        document.getElementById('greeting').innerHTML = "Hello, " + userName + ".";    
    else
        document.getElementById('greeting').innerHTML = "Hello.";            
});
