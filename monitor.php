<?php
// These functions are extracted from the script you provided earlier
// ... [other functions like get_device_model, get_info, etc.]

function check_alerts() {
    global $D;
    $alerts = array();

    // CPU usage alert
    $cpu_usage = ($D['cpu']['stat']['user'] + $D['cpu']['stat']['sys'] + $D['cpu']['stat']['irq'] + $D['cpu']['stat']['softirq']) / 
                 ($D['cpu']['stat']['user'] + $D['cpu']['stat']['sys'] + $D['cpu']['stat']['idle'] + $D['cpu']['stat']['iowait'] + $D['cpu']['stat']['irq'] + $D['cpu']['stat']['softirq']) * 100;
    if ($cpu_usage > 90) {
        $alerts[] = "High CPU Usage: " . round($cpu_usage) . "%";
    }

    // CPU temperature alert
    if ($D['cpu']['temp'][0] / 1000 > 70) {
        $alerts[] = "High CPU Temperature: " . ($D['cpu']['temp'][0] / 1000) . "°C";
    }

    // Memory usage alert
    if ($D['mem']['percent'] > 85) {
        $alerts[] = "High Memory Usage: " . $D['mem']['percent'] . "%";
    }

    // Disk usage alert
    if ($D['disk']['percent'] > 90) {
        $alerts[] = "Low Disk Space: " . $D['disk']['percent'] . "% used";
    }

    return $alerts;
}

// Execute the check function
$alerts = check_alerts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Monitor</title>
    <script type="text/javascript">
        var alerts = <?php echo json_encode($alerts); ?>;
    </script>
</head>
<body>

<div id="alerts"></div>

<script type="text/javascript">
    window.onload = function() {
        var alertsContainer = document.getElementById('alerts');

        if (alerts.length > 0) {
            alerts.forEach(function(alert) {
                var alertElement = document.createElement('div');
                alertElement.className = 'alert';
                alertElement.textContent = alert;
                alertsContainer.appendChild(alertElement);
            });
        } else {
            alertsContainer.textContent = 'No alerts';
        }
    };
</script>

</body>
</html>
