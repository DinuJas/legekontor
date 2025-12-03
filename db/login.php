<?php

session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"]);
    $passord = trim($_POST["passord"]);

    $stmt = $conn->prepare("SELECT bruker_id, passord FROM bruker WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows === 1)
    {
        $bruker = $result->fetch_assoc();

        // Sjekk om det er lege
        $stmt = $conn->prepare("SELECT lege_id FROM lege WHERE bruker_id = ?");
        $stmt->bind_param("i", $bruker["bruker_id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        $lege = $result->fetch_assoc();

        // Sjekk om det er pasient
        $stmt = $conn->prepare("SELECT pasient_id FROM pasient WHERE bruker_id = ?");
        $stmt->bind_param("i", $bruker["bruker_id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        $pasient = $result->fetch_assoc();

        // Verify password
        if (password_verify($passord, $bruker["passord"]))
        {
            // Store user session
            $_SESSION["bruker_id"] = $bruker["bruker_id"];
            $_SESSION["email"] = $email;

            // Send til lege dashboard om lege også til pasient dashboard om pasient
            if ($lege)
            {
                header("Location: ../lege_dashboard.php");
                exit;
            }
            else if ($pasient)
            {
                header("Location: ../pasient_dashboard.php");
                exit;
            }
        }
        else
        {
            echo "Invalid password <a href='../login.html'>Login here</a>";
        }
    }
    else
    {
        echo "Incorrect email or password <a href='../login.html'>Login here</a>";
    }

    $stmt->close();
    $conn->close();
}

?>