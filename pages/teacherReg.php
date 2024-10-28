<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="main-body register">
    <div class="reg-wrapper">
        <div class="reg-headline">
            <h2>Registere here</h2>
        </div>
        <div class="reg-img teach">
            <img src="../assets/image/icon/teacher.png">
        </div>
        <div class="reg-info-container">
            <form action="register.php" method="$_GET">
                <div class="std-teach name">
                    <label for="name">Name:</label>
                    <input type="text" id="reg-std-name-value" required>
                </div>
                <div class="std-teach roll">
                    <label for="roll-num">Roll-num:</label>
                    <input type="text" id="reg-std-id-value" required>
                </div>
                <div class="std-teach email">
                    <label for="email">Email:</label>
                    <input type="text" id="reg-std-email-value" required>
                </div>
                <div class="std-teach phone">
                    <label for="phone">Phone:</label>
                    <input type="number" id="reg-std-phone-value" required>
                </div>
                <div class="std-teach pw">
                    <label for="email">Password:</label>
                    <input type="password" id="reg-std-pw-value" required>
                </div>
                <div class="std-teach repeat-pw">
                    <label for="repeat-pw">Repeat Password:</label>
                    <input type="password" id="reg-std-r-pw-value" required>
                </div>
                <div class="register-btn">
                    <button class="btn reg-btn">Register</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>