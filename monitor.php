<?php
function check_alerts() {
    global $D;
    $alerts = array();

    // CPU 使用率警告
    $cpu_usage = ($D['cpu']['stat']['user'] + $D['cpu']['stat']['sys'] + $D['cpu']['stat']['irq'] + $D['cpu']['stat']['softirq']) / 
                 ($D['cpu']['stat']['user'] + $D['cpu']['stat']['sys'] + $D['cpu']['stat']['idle'] + $D['cpu']['stat']['iowait'] + $D['cpu']['stat']['irq'] + $D['cpu']['stat']['softirq']) * 100;
    if ($cpu_usage > 90) {
        $alerts[] = "高 CPU 使用率: " . round($cpu_usage) . "%";
    }

    // CPU 溫度警告
    if ($D['cpu']['temp'][0] / 1000 > 70) {
        $alerts[] = "CPU 溫度過高: " . ($D['cpu']['temp'][0] / 1000) . "°C";
    }

    // 記憶體使用警告
    if ($D['mem']['percent'] > 85) {
        $alerts[] = "高記憶體使用率: " . $D['mem']['percent'] . "%";
    }

    // 磁盤使用警告
    if ($D['disk']['percent'] > 90) {
        $alerts[] = "磁盤空間不足: " . $D['disk']['percent'] . "% 已使用";
    }

    return $alerts;
}

// 執行檢查功能
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
