<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["email"]))
{
    die("Du er ikkje logga inn");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $fornamn = trim($_POST["fornamn"]); 
    $etternamn = trim($_POST["etternamn"]);
    $rolle = trim($_POST["rolle"]);

    // Hent bruker_id via email
    $stmt = $conn->prepare("SELECT bruker_id FROM bruker WHERE email = ?");
    $stmt->bind_param("s", $_SESSION["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $bruker_id = $row["bruker_id"];

    // Legg til lege
    if ($rolle == "lege") {
        $stmt = $conn->prepare("
            INSERT INTO lege (fornamn, etternamn, bruker_id) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $fornamn, $etternamn, $bruker_id);
        $stmt->execute();

        header("Location: ../lege_dashboard.php");
    } 
    else if ($rolle == "pasient") {
        $stmt = $conn->prepare("
            INSERT INTO pasient (fornamn, etternamn, bruker_id)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $fornamn, $etternamn, $bruker_id);
        $stmt->execute();

        header("Location: ../pasient_dashboard.php");
    } 
    else {
        echo "Something went wrong";
    }
}
?>
