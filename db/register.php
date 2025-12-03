<?php

session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Få data ifrå skjema
    $email = trim($_POST["email"]);
    $passord = trim($_POST["passord"]);

    // Få tak i alle brukerar i databasen
    $stmt = $conn->prepare("SELECT bruker_id FROM bruker WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Sjekkar om denne brukeren er registrert frå før av
    if ($stmt->num_rows > 0)
    {
        echo "Email is already registered! <a href='../register.html'>Register here</a>";
    }
    else
    {    
        // Hash passord før eg lagre den i databasen
        $hashedpassord = password_hash($passord, PASSWORD_DEFAULT);

        // legg til ny bruker i database
        $stmt = $conn->prepare("INSERT INTO bruker (email, passord) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedpassord);
        
        if ($stmt->execute())
        {
            echo "Registration successful! Legg til info <a href='../leggtilinfo.html'>Legg til info</a>";

            // Lagre info til session
            $_SESSION["email"] = $email;
        }
        else
        {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}

?>