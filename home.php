<!-- http://stackoverflow.com/questions/20877311/change-css-background-image-with-php -->

<!DOCTYPE html>
<html>
<head>
    <title>Antrikshy's Browser Start Page</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="./jquery/2.1.1/jquery-2.1.1.min.js"></script>
    <script src="./jquery/jquery.cookie.js"></script>
    <script src="./scripts.js"></script>


    <link rel="stylesheet" href="./styles.css" />
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"> -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
   
    <?php
    include('./forecast.io.php');
    
    $apikey = parse_ini_file('forecastKey.ini')['apikey'];

    $userLat = NULL;
    $userLong = NULL;
    $isNight = NULL;

    $clearDayTips = array(
                     "Looks bright and sunny outside right now.",
                     "It's a clear day for now.",
                     "You shouldn't need an umbrella right now.",
                     "The day is beautiful, just like you.",
                     "You will walk into a clear, sunny day if you leave now."
                    );

    $clearNightTips = array(
                     "The night is clear right now.",
                     "Weather should be clear about now.",
                     "You shouldn't need an umbrella right now.",
                     "You will be greeted by a beautiful evening if you wander outside.",
                     "The night is beautiful, just like you."
                    );

    $partlyCloudyTips = array(
                     "There is some cloud cover right now, but it should be fine otherwise.",
                     "Other than some clouds here and there, the weather should be totally fine.",
                     "I see a few clouds above."
                    );

    $cloudyTips = array(
                     "It looks like an overcast day. My favorite!",
                     "It's cloudy outside, currently.",
                     "The sky is full of clouds right now.",
                     "The sky seems overcast where you are. If I were human, I'd get some photos taken outside."
                    );

    $fogTips = array(
                     "It looks like there's some fog where you are right now.",
                     "It's foggy outside. Drive slow!",
                     "If you walk outside, you may be greeted by some gorgeous white fog right now."
                    );

    $rainTips = array(
                     "Looks like it's raining right now. Better grab an umbrella!",
                     "Remember to take an umbrella if you decide to leave now.",
                     "It's pouring outside.",
                     "If I were you, I'd carry an umbrella when heading out.",
                     "If you're leaving now, take an umbrella with you.",
                     "Current weather looks poor. The rainy kind."
                    );

    $snowTips = array(
                     "Expect some snow if you go out.",
                     "I hope you have some cold-weather clothes handy if you want to leave now.",
                     "There may be some snowfall outside right now.",
                     "I recommend wearing a thick jacket if you decide to wander outside right now."
                    );

    $sleetTips = array(
                     "Weather looks pretty rough. Expect frozen water from the sky.",
                     "Except some sleet if you go out.",
                     "There may be some sleet right now. Proceed with caution.",
                     "Consider bringing an umbrella for the sleet."
                    );

    $windTips = array(
                     "It might be windy outside, but fine otherwise.",
                     "Be advised, the strong breeze currently outside may ruin your hairdo.",
                     "It's windy outside, currently. I recommend wearing a jacket."
                    );

    $weatherToTipsArray = array (
                     'clear-day' => $clearDayTips,
                     'clear-night' => $clearNightTips,
                     'rain' => $rainTips,
                     'snow' => $snowTips,
                     'sleet' => $sleetTips,
                     'wind' => $windTips,
                     'fog' => $fogTips,
                     'cloudy' => $cloudyTips,
                     'partly-cloudy-day' => $partlyCloudyTips,
                     'partly-cloudy-night' => $partlyCloudyTips
                    );

    if (isset($_COOKIE['longitude']) && isset($_COOKIE['latitude'])) {
        $userLat = $_COOKIE['latitude'];
        $userLong = $_COOKIE['longitude'];
    }

    else {
        if ($_GET['latitude'] == 'error' && $_GET['longitude'] == 'error') {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $userIP = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $userIP = $_SERVER['REMOTE_ADDR'];
            }

            $IPInfoLoc = json_decode(file_get_contents('http://ipinfo.io/{$userIP}/loc'));
            $IPInfoArray = explode(",", $IPInfoLoc);
            $userLat = $IPInfoArray[0];
            $userLong = $IPInfoArray[1];
        }

        else {
            $userLat = $_GET['latitude'];
            $userLong = $_GET['longitude'];
        }
    }

    $forecast = new ForecastIO($apikey);

    if($userLat != null && $userLong != null) {
        $units = 'si';
        ($_COOKIE['tempPref'] == 'F' ? $units = 'us' : pass);
        
        $condition = $forecast->getCurrentConditions($userLat, $userLong, $units);
        $weatherCondition = $condition->getIcon();
        // $weatherCondition = 'rain';
        
        $backgroundImageURL = './weather-backgrounds/' . $weatherCondition . '.jpg';

        if (in_array($weatherCondition, ['clear-night', 'partly-cloudy-night']))
            $isNight = True;
    }
    ?>

    <div id="prefs_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h2 class="modal-title">Preferences</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="prefs_form" style="margin-left:20px;">
                            <fieldset>
                                <label>Name</label>
                                <input type="text" class="input-xlarge" name="userName" value=<?php echo $_COOKIE['userName']; ?>><br />
                                <label>Preferred units for temperature</label>
                                <input type="radio" name="tempUnits" value="C"> &deg;C
                                <input type="radio" name="tempUnits" value="F"> &deg;F
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="save_prefs" type="submit" class="btn btn-primary" data-toggle="modal" data-target="#prefs_modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="controls_bar" class="navbar navbar-default navbar-fixed-top container-fluid">
        <button type="button" id="site_prefs" class="btn btn-primary navbar-btn" data-toggle="modal" data-target="#prefs_modal">
            Preferences
        </button>
    </div>
    <div id="container" class="container-fluid" style="background-image:url(<?php echo $backgroundImageURL;?>);">
        <div id="main_and_content" class="container-fluid">
            <div class="row" style="<?php if ($isNight) echo 'color:white; text-shadow:2px 2px black;'?>;"> 
                <div id="greeting" class="col-md-7 col-md-offset-5"></div>
                <div id="weather_conditions" class="col-md-7 col-md-offset-5">
                    <?php
                    if ($userLat != null && $userLong != null)
                        print $weatherToTipsArray[$weatherCondition][array_rand($weatherToTipsArray[$weatherCondition])];
                    ?>
                </div>
            </div>
        </div>
        <div id="content_bar" class="container-fluid navbar-fixed-bottom" style="<?php if ($isNight) echo 'color:white;'?>;">
            <div id="date_temp" class="row">
                <div id="time" class="col-md-8"></div>
                <?php
                if ($userLat != null && $userLong != null)
                    print '<div id="curr_temp" class="col-md-4">' . $condition->getTemperature() .
                            '<span style="font-size:.75em"> &deg;' . (isset($_COOKIE['tempPref']) ? $_COOKIE['tempPref'] : 'C'). '</span></div>';
                else
                    print '<div id="curr_temp" class="col-md-4" style="font-size:4em">Error fetching temperature</div>';
                ?>
            </div>
            <div id="date_container" class="row">
                <div id="date" class="col-md-5"></div>
            </div>
        </div>
    </div>
</body>
</html>