<!DOCTYPE html>
<html>
<head>
    <title>Form Submission</title>
</head>
<body>
    <form action="" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="id_name" required>
        
        <label for="mobile">Mobile</label>
        <input type="tel" name="mobile" maxlength="10" minlength="10" id="id_mobile" required onkeypress="return isNumber(event)">
        
        <label for="email">Email</label>
        <input type="email" name="email" id="id_email" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" maxlength="6" minlength="6" pattern="\d{6}" id="id_password" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
        
        <input type="submit" name="btn" value="Submit">
    </form>

    <script>
        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn'])) {
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        echo 'Form data: ' . htmlspecialchars($name) . ' | ' . htmlspecialchars($mobile) . ' | ' . htmlspecialchars($email) . ' | ' . htmlspecialchars($password) . '<br>';

        if (!preg_match("/^\d{10}$/", $mobile)) {
            $errors[] = 'Please enter a valid 10-digit mobile number.';
        }

        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($emailRegex, $email)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (empty($errors)) {
            $servername = "127.0.0.1";
            $username = "admin";
            $dbpassword = "admin123";
            $database = "test";

            // Create connection
            $conn = new mysqli($servername, $username, $dbpassword, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                echo 'testprint<br>';

                $stmt = $conn->prepare("INSERT INTO tbl_user (name, mobile, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $mobile, $email, $password);

                if ($stmt->execute()) {
                    echo "New record created successfully<br>";
                } else {
                    echo "Error: " . $stmt->error . "<br>";
                }

                $stmt->close();
            }
            $conn->close();
        } else {
            foreach ($errors as $error) {
                echo '<p style="color:red;">' . $error . '</p>';
            }
        }
    }
    ?>
</body>
</html>
