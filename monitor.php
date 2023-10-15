<?php
function get_cpu_usage() {
    // Dummy function: replace with actual logic to get CPU usage
    return 85; // Example: CPU usage is 85%
}

function check_system_status() {
    $alerts = array();

    $cpu_usage = get_cpu_usage();

    if ($cpu_usage > 80) {
        $alerts[] = "High CPU usage: $cpu_usage%";
    }

    // Add other system checks here as needed

    return $alerts;
}

$alerts = check_system_status();
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
