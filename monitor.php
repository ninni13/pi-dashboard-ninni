<?php
// Read settings from the JSON file
$settings = json_decode(file_get_contents('settings.json'), true);

function getSystemInfo() {
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

function check_system_status($settings) {
    $alerts = array();

    $data = getSystemInfo();

    // CPU check
    if ($settings['monitor_items']['cpu'] && $data['cpu']['percent'] > $settings['cpu_threshold']) {
        $alerts[] = "High CPU usage: {$data['cpu']['percent']}%";
    }

    // RAM check
    if ($settings['monitor_items']['ram'] && $data['mem']['percent'] > $settings['ram_threshold']) {
        $alerts[] = "High RAM usage: {$data['mem']['percent']}%";
    }

    // Disk check
    if ($settings['monitor_items']['disk'] && $data['disk']['percent'] > $settings['disk_threshold']) {
        $alerts[] = "High Disk usage: {$data['disk']['percent']}%";
    }

    return $alerts;
}

$alerts = check_system_status($settings);
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

<!-- User Settings Form -->
<form action="monitor.php" method="POST">
    <label for="cpu_threshold">CPU Threshold (%):</label>
    <input type="number" id="cpu_threshold" name="cpu_threshold" value="<?php echo $settings['cpu_threshold']; ?>" min="1" max="100">
    
    <label for="ram_threshold">RAM Threshold (%):</label>
    <input type="number" id="ram_threshold" name="ram_threshold" value="<?php echo $settings['ram_threshold']; ?>" min="1" max="100">
    
    <label for="disk_threshold">Disk Threshold (%):</label>
    <input type="number" id="disk_threshold" name="disk_threshold" value="<?php echo $settings['disk_threshold']; ?>" min="1" max="100">
    
    <input type="submit" value="Update Settings">
</form>

<?php
// If form is submitted, update the settings.json file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $settings['cpu_threshold'] = $_POST['cpu_threshold'];
    $settings['ram_threshold'] = $_POST['ram_threshold'];
    $settings['disk_threshold'] = $_POST['disk_threshold'];
    file_put_contents('settings.json', json_encode($settings));
}
?>

</body>
</html>
