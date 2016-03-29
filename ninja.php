<?php
$ninjaConfig = [
    'siteUrl' => 'www.clm.io',
    'environment' => 'test'
];

$templateValues['ninja'] = [
    'config' => $ninjaConfig,
    'js' => $ninjaVars,
    'ns' => getNoScriptUrl($ninjaVars, $ninjaConfig, false),
    'nsForWap' => (isset($templateValues['isWap'])) ? getNoScriptUrl($ninjaVars, $ninjaConfig, true) : ''
];

function getNoScriptUrl($ninjaVars, $ninjaConfig, $setSessionCookies) {
    // Set the host
    if ($ninjaConfig['environment'] == 'test') {
        $hydraHost = 'tracking-dev.onap.io/h/';
    } else {
        $hydraHost = 'tracking.olx-st.com/h/v2/';
    }

    // Set the URL with the correct path
    $url = 'http://' . $hydraHost . 'ns-demo?';

    // Set the sessions
    if (isset($_COOKIE['onap'])
        && ! empty($_COOKIE['onap'])
        && (preg_match("/([a-z0-9]+)-([0-9]+)-([a-z0-9]+)-([0-9]+)-([0-9]+)-?(.*)?/", $_COOKIE['onap'], $sessionValues)))  {
        $sessionLong = $sessionValues[1];
        $sessionCountLong = (int) $sessionValues[2];
        $session = $sessionValues[3];
        $sessionCount = (int) $sessionValues[4];
        $sessionExpired = $sessionValues[5];
        $sessionExtra = (isset($sessionValues[6]) && ! empty($sessionValues[6])) ? $sessionValues[6] : null;

        if ($sessionExpired - time() > 0)  {
            $sessionCount++;
        }  else  {
            $session = generateSession();
            $sessionCount = 1;
            $sessionCountLong++;
        }
        $newUser = false;
    }  else  {
        $sessionLong = generateSession();
        $session = $sessionLong;
        $sessionCount = 1;
        $sessionCountLong = 1;
        $sessionExtra = null;
        $newUser = true;
    }

    // Fill the final array
    $tmp = array();

    // Add session vars
    if ($setSessionCookies)  {
        $cookieValue = $sessionLong . '-' . $sessionCountLong . '-' . $session . '-' . $sessionCount . '-' . (time() + 1800);
        if (null !== $sessionExtra)  {
            $cookieValue .= '-' . $sessionExtra;
        }
        $cookieValue = preg_replace('/[^\w\-\=]/', '', $cookieValue);
        // Use the correct domain
        setrawcookie('onap', $cookieValue, strtotime('+1 year'), '/', '.www.clm.io');
        $tmp['sl'] = $sessionLong;
        $tmp['s'] = $session;
        $tmp['cl'] = $sessionCountLong;
        $tmp['c'] = $sessionCount;
    }

    // Add New user
    if ($newUser)  {
        $tmp['nw'] = 1;
    }

    // Add config and vars
    $params = array_merge($ninjaConfig, $ninjaVars);
    foreach ($params as $key => $value)
    {
        if (is_scalar($value))  {
            $tmp[$key] = $value;
        } else  {
            $tmp[$key] = @json_encode($value);
        }
    }

    // Add invite
    if (preg_match("/(.*)invite=(.*)&?/", $_SERVER['REQUEST_URI'], $matches)) {
        $tmp['iv'] = trim($matches[2]);
    }

    // Add timestamp
    date_default_timezone_set('Antarctica/South_Pole');
    $tmp['t'] = time();

    // Return the CODE
    return  $url . http_build_query($tmp);
}

function generateSession() {
    return base_convert(microtime(true) * 10000, 10, 16) . 'x' . base_convert(mt_rand(), 10, 16);
}

