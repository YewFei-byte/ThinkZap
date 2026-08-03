        <?php
        session_start();
        if (!isset($_SESSION['adminName'])) {
            header('Location: login.php');
            exit;
        }

        $adminName = $_SESSION['adminName'];
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome</title>
            <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #fff9c4; 
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h1 {
            top: 10px;
            margin-bottom: 50px;
            color: #000000; 
            text-align: center;
        }

        .dialog {
            top: 10px;
            background-color: #ffffff;
            padding: 20px 40px;
            border: 2px solid #4caf50;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fade-out 1.2s ease-in-out forwards;
        }
        @keyframes fade-out {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

    </style>
    
</head>
<body>
    <div class="dialog">
        <h1>Welcome, <?php echo htmlspecialchars($adminName); ?>!</h1>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'adminPage.php';
        }, 1200);
    </script>
</body>
</html>
