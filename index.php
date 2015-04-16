<!DOCTYPE HTML>
<html>
<head>
    <title>Team Hex Codes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet" type="text/css">
</head>

<body>
    <header id="header">
        <p class="intro-authors"><a href="http://twitter.com/jimniels">@jimniels</a> + <a href="http://lab.arc90.com/">Arc90 Lab</a> present:</p>
        <h1 class="intro-description"><strong>Team Colors:</strong> Find and copy HEX color approximations from your favorite teams.</h1>
    </header>

    <nav id="navigation">
        <ul class="leagues">
            <?php 
                // Get the data for all the leagues and teams
                $leagues = json_decode(file_get_contents('team-data.json'), true);

                // Loop over the data
                foreach ($leagues as $league => $teams) { 
                    ?>
                    <li id="nav-<?= strtolower($league) ?>" class="nav-league">
                        <a href="#<?= strtolower($league) ?>" class="nav-league__name"><?= $league ?></a>
                    </li>
                    <?php 
                } 
            ?>
        </ul>
    </nav>

    <ul id="content" class="teams">
    <?php
        
        // Loop over the data, outputting the teams by league 
        foreach ($leagues as $league => $teams) { 
            ?>
            <li>
                <h2 id="<?= strtolower($league) ?>"><?= $league ?></h2>
                <ul>
                <!-- Inline block spacing 
                     http://css-tricks.com/fighting-the-space-between-inline-block-elements/
                <?php 
                    foreach($teams as $team => $colors) {
                        $teamID = convertNameToId($team);

                        if($colors) {  
                            ?>
                            -->
                            <li class="team" id="<?= $teamID ?>" data-team-id="<?= $team ?>" data-league="<?= strtolower($league) ?>">
                                <h3 id="<?= $teamID ?>" class="team__name" data-logo-url="img/build/<?= strtolower($league) . '/' . $teamID ?>.svg"><?= $team ?></h3>

                                <ul class="colors">
                                    <?php 
                                        for ($i=0; $i < count($colors['rgb']); $i++) { 
                                            // Get the RGB, HEX, CMYK, and PMS values
                                            // Then turn them into user-facing strings
                                            $rgb = explode(" ", $colors['rgb'][$i]);
                                            $hex = rgb2hex($rgb[0], $rgb[1], $rgb[2]);
                                            $cmyk = explode(" ", $colors['cmyk'][$i]);
                                            $pms = $colors['pms'][$i];

                                            // RGB - RGB(x,x,x)
                                            $rgbStr = "RGB(" . $rgb[0] . "," . $rgb[1] . "," . $rgb[2] . ")";
                                            // HEX - #045743
                                            $hexStr = "#" . $hex;
                                            // CMYK - CMYK(0,12,43,54)
                                            $cmykStr = "CMYK(" . $cmyk[0] . "," . $cmyk[1] . "," . $cmyk[2] . "," . $cmyk[3] . ")";
                                            // Pantone - PMS 254
                                            $pmsStr = "PMS " . $colors['pms'][$i];
                                            ?>
                                            <li class="color" 
                                                data-hex="<?= $hexStr ?>" 
                                                data-rgb="<?= $rgbStr ?>" 
                                                data-cmyk="<?= $cmykStr ?>"
                                                data-pms="<?= $pmsStr ?>">
                                                <?= $hexStr ?>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </li>
                            <!--
                            <?php 
                        }
                    }
                ?>
                end inline block spacing -->
                </ul>
            </li>
            <?php 
        } //end $leagues foreach
    ?>
    </ul>

    <footer id="footer">
        <p>Want to add a new team or league? Want to make a correction? <a href="https://github.com/teamcolors/teamcolors.github.io">Find the source code on Github &raquo;</a></p>
    </footer>

    <!-- Script includes -->
    <script src="js/jquery1.8.js"></script>
    <script src="js/js.js"></script>
    <script src="js/loadcss.js"></script>
    <script src="js/lazyload.js"></script>

    <!-- Google Analytics -->
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-2118091-16']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</body>
</html>

<?php

/**
 * Convert Name to ID
 * Takes team names like "Atlanta Hawks"
 * Converts to "atlanta-hawks"
 */
function convertNameToID($name) {
    $name = str_replace(" ","-",$name);
    $name = strtolower($name);
    return $name;
}

/**
 * Convert HEX to RGB
 * http://css-tricks.com/snippets/php/convert-hex-to-rgb/
 */
function hex2rgb( $colour ) {
    if ( $colour[0] == '#' ) {
            $colour = substr( $colour, 1 );
    }
    if ( strlen( $colour ) == 6 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
    } elseif ( strlen( $colour ) == 3 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
    } else {
            return false;
    }
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    //return array( 'red' => $r, 'green' => $g, 'blue' => $b );
    return "rgb($r,$g,$b)";
}

function rgb2hex($r, $g=-1, $b=-1) {
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return $color;
}