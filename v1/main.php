<!--
Author: Maël Guiraud
17/03/2023
Property of CESI Nanterre
-->

<?php

$serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "confort";

//Check if get room value is corrupted
if (isset($_GET['room'])) {
    if (is_numeric($_GET["room"])){
        $roomget = htmlspecialchars($_GET["room"]);
    }
    else{
        echo "<script>alert(\"Vous tentez d'utiliser une URL corrompue.\")</script>";
        exit(1);
    }
    
} else {
    $roomget = 10;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check if POST values are corrupted
    if (!is_numeric($_POST["level"])&&!is_numeric($_POST["reason"])&&!is_numeric($_POST["heat"])&&!is_numeric($_POST["light"])&&!is_numeric($_POST["room"])){
        echo "<script>alert(\"Les données du formulaire sont corrompues.\")</script>";
        exit(1);
    }
    $level =  htmlspecialchars($_POST["level"]);
    $reason =  htmlspecialchars($_POST["reason"]); //binary flags
    $heat =  htmlspecialchars($_POST["heat"]);
    $light =  htmlspecialchars($_POST["light"]);
    $room =  htmlspecialchars($_POST['room']);
    

    //DataBase connect
    $connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Check 
    if (!$connexion) {
        die("Connexion échouée: " . mysqli_connect_error());
    }

    // SQL Request
    $sql = "INSERT INTO feedback_confort (room_id, confort_level,reason,heat,light) VALUES ('$room', '$level','$reason','$heat','$light')";

    // Check
    if (!mysqli_query($connexion, $sql)) {
        echo "Un problème est survenu lors de l'envoi du formulaire. Merci de contacter Maël Guiraud.";
        exit(1);
    }
    mysqli_close($connexion);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Formulaire d'évaluation du confort.</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./aa.css" , type="text/css"/>
    <script type="text/javascript" src="./script.js"></script>
</head>

<body class="container">
    <div class="formu">
        <!-- Form mad of buttons managed with JS. The informations are stored in hidden inputs at the submit call the function onSend() 
        that put the value of selected buttons in the right inputs. -->
        <form
            style="background-color: rgba(255, 255, 255, 0.7); border-radius: 12px; border:2px solid black;width :80vw;"
            method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?room=" . $roomget; ?>"
            onsubmit="return onSend()" class="col-12">
            <!-- Main question -->
            <div class="row">
                <h1 class="col-12 text-center">Comment évalueriez-vous le confort dans la salle ?</h1>
            </div>
            <div class="row justify-content-around">
                <button type="button" id="mark0" class="col-2 smiley general_confort">&#128545;</button>
                <button type="button" id="mark1" class="col-2 smiley general_confort">&#128542;</button>
                <button type="button" id="mark2" class="col-2 smiley general_confort">&#128528;</button>
                <button type="button" id="mark3" class="col-2 smiley general_confort">&#128578;</button>
                <button type="button" id="mark4" class="col-2 smiley general_confort">&#128522;</button>
                <input type="hidden" id="level_hidden" name="level" />
            </div>
            <!-- First option -->
            <div id="discomfort_kind" class="hidden">
                <div class="row">
                    <h1 class="col-12 text-center">Quelles sont les raisons de votre inconfort ?</h1>
                </div>
                <div class="row justify-content-around">
                    <button type="button" id="reason_1" class="col-2 smiley hb">&#127777;</button>
                    <button type="button" id="reason_2" class="col-2 smiley lb"> &#128161;</button>
                    <button type="button" id="reason_4" class="col-2 smiley sb">&#128266;</button>
                    <input type="hidden" id="reason_hidden" name="reason" />
                </div>
                <!-- heat option -->
                <div id="heat" class="hidden row ">
                    <div class="row text-center">
                        <h1 class="col-12 ">Aviez vous trop froid où trop chaud ?</h1>
                    </div>
                    <div class="row justify-content-around">
                        <button type="button" id="heat_0" class="col-2 smiley cold temper">&#129398;</button>
                        <button type="button" id="heat_1" class="col-2 smiley hot temper">&#129397;</button>
                    </div>
                    <input type="hidden" id="heat_hidden" name="heat" value="-1" />
                </div>
                <!-- light option -->
                <div id="light" class="hidden row ">
                    <div class="row text-center">
                        <h1 class="col-12 ">Faisait-il sombre où trop lumineux ?</h1>
                    </div>
                    <div class="row justify-content-around">
                        <button type="button" id="light_0" class="col-2 smiley low_l lumi">&#9729;</button>
                        <button type="button" id="light_1" class="col-2 smiley high_l lumi">&#9728;</button>
                    </div>

                    <input type="hidden" id="light_hidden" name="light" value="-1" />
                </div>
            </div>
            <div class="row justify-content-center">
                <input type="hidden" name="room" value="<?php echo $roomget; ?>" />
                <button type="submit" class="col-2 subm">Envoyer</button>
            </div>
        </form>
    </div>


    <!-- Image credits and copyright -->
    <div class="row fixed-bottom ">
        <div class="col-6 text-sm-start " , style="color:white;font-size:1vh;">
            <p class="text-left" , style=" margin-left:1vw;">
            <a href="https://www.freepik.com/free-vector/yellow-halftone-triangle-pattern-background_24373192.htm#page=3&query=background%20repeat%20yellow%20design&position=48&from_view=search&track=ais"
                    , style="color:white;">Image by starline</a> on Freepik
            </p>
        </div>
        <div class="col-6 text-sm-end " , style="color:white;font-size:1vh;">
            <p class="text-right mr-2"> &copy <a href="https://mael-guiraud.github.io/" , style="color:white">Maël
                    Guiraud</a>
            </p>
        </div>
    </div>
</body>

</html>