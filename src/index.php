<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scalable Web App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }
        
        .header {
            color: #333;
            margin-bottom: 2rem;
        }
        
        .server-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid #007bff;
        }
        
        .db-status {
            margin: 1.5rem 0;
            padding: 1rem;
            border-radius: 8px;
            font-weight: bold;
        }
        
        .connected {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .disconnected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .visitor-form {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .form-group {
            margin: 1rem 0;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: bold;
        }
        
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .btn {
            background: #007bff;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .visitors-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .visitors-table th, .visitors-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .visitors-table th {
            background: #007bff;
            color: white;
            font-weight: bold;
        }
        
        .visitors-table tr:hover {
            background: #f5f5f5;
        }
        
        .load-test-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 4px solid #2196f3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Scalable 2-Tier Web Application</h1>
            <p>Powered by AWS EC2, RDS, ALB & Auto Scaling</p>
        </div>

        <div class="server-info">
            <h3>Server Information</h3>
            <p><strong>Instance ID:</strong> <?php echo file_get_contents('http://169.254.169.254/latest/meta-data/instance-id'); ?></p>
            <p><strong>Availability Zone:</strong> <?php echo file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone'); ?></p>
            <p><strong>Instance Type:</strong> <?php echo file_get_contents('http://169.254.169.254/latest/meta-data/instance-type'); ?></p>
            <p><strong>Local IP:</strong> <?php echo file_get_contents('http://169.254.169.254/latest/meta-data/local-ipv4'); ?></p>
            <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <?php
        // Database configuration - Replace with your actual RDS endpoint
        $servername = "scalable-webapp-db.ctsu4w242s4r.ap-south-1.rds.amazonaws.com";  #Endpoint of RDS
        $username = "admin"; 
        $password = "ScalableApp123!";
        $dbname = "webappdb"; #Tablename of RDS
        
        $conn = null;
        $db_connected = false;
        
        try {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            $db_connected = true;
            
            // Create visitors table if not exists
            $sql = "CREATE TABLE IF NOT EXISTS visitors (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                message TEXT,
                visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                server_id VARCHAR(50)
            )";
            $conn->query($sql);
            
            // Handle form submission
            if ($_POST['name'] && $_POST['email']) {
                $name = $conn->real_escape_string($_POST['name']);
                $email = $conn->real_escape_string($_POST['email']);
                $message = $conn->real_escape_string($_POST['message']);
                $server_id = file_get_contents('http://169.254.169.254/latest/meta-data/instance-id');
                
                $sql = "INSERT INTO visitors (name, email, message, server_id) VALUES ('$name', '$email', '$message', '$server_id')";
                if ($conn->query($sql) === TRUE) {
                    echo "<div style='background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin: 1rem 0;'>Visitor information saved successfully!</div>";
                }
            }
            
        } catch (Exception $e) {
            $db_connected = false;
            $error_message = $e->getMessage();
        }
        ?>

        <div class="db-status <?php echo $db_connected ? 'connected' : 'disconnected'; ?>">
            <?php if ($db_connected): ?>
                ✅ Database Connected Successfully
            <?php else: ?>
                ❌ Database Connection Failed: <?php echo isset($error_message) ? $error_message : 'Unknown error'; ?>
            <?php endif; ?>
        </div>

        <?php if ($db_connected): ?>
            <form class="visitor-form" method="POST">
                <h3>Leave Your Mark! 📝</h3>
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="3" placeholder="Tell us what you think about this scalable app!"></textarea>
                </div>
                <button type="submit" class="btn">Submit</button>
            </form>

            <div style="margin-top: 2rem;">
                <h3>Recent Visitors</h3>
                <?php
                $sql = "SELECT name, email, message, visit_time, server_id FROM visitors ORDER BY visit_time DESC LIMIT 10";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0): ?>
                    <table class="visitors-table">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Time</th>
                            <th>Server</th>
                        </tr>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo $row['visit_time']; ?></td>
                            <td><?php echo substr($row['server_id'], -8); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No visitors yet. Be the first!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="load-test-info">
            <h4>🔧 For Load Testing</h4>
            <p>Use this endpoint: <code>/load-test.php</code></p>
            <p>Refresh this page to see load balancing in action!</p>
        </div>
    </div>

    <script>
        // Auto refresh every 30 seconds to show load balancing
        setTimeout(function(){
            location.reload();
        }, 30000);
        
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.transform = 'scale(0.9)';
            container.style.opacity = '0';
            
            setTimeout(function() {
                container.style.transition = 'all 0.5s ease-out';
                container.style.transform = 'scale(1)';
                container.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>

