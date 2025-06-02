<html>

<head>
    <title>Welcome to Peptalk</title>
    <link rel="icon" href="images/analysis.png">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function resetForm() {
        document.getElementById("form_id").reset();
    }
    </script>
</head>


    <body>
        <div class="container">
            <div class="container">
                <section id="content">
                    <form action="loginprocess.php" method="POST" id="form_id">
                        <h1>Login Form</h1>

                        <div>
                            <input type="text" class="form-control" required="" name="emp_num" placeholder=" Username" id="username"
                                aria-label="Large">
                        </div>

                        <div>
                            <input type="password" class="form-control" name="passwd" placeholder=" Password" id="password"
                                aria-label="Large" required="">

                        </div>
                        
                        <div>
                            <input type="submit" class="btn btn-success btn-lg" value="LOGIN" name="sub">
                            <a onclick="resetForm()" value="RESET"><u>Reset</u></a>                           
                        </div>
                    </form>
                </section>
            </div>            
        </div>
    </body>
   
</html>
