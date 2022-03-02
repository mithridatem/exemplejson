<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/page_decks.css"/>
    <script src="../script/script_display.js" defer></script>
    <title>Create decks</title>
</head>
<body>
    <header> 
        <nav class="navbar">
            <h1>LOGO</h1>
                <ul>
                    <li><a href="page_login">Sign In</a></li>
                    <li><a href="page_login">Sign Up</a></li>
                    <li><a href="page_signIn">Cartes</a> </li>               
                    <li><a href="../MTG_Project/page_decks.html">Decks</a></li>             
                </ul>
            <h2>SEARCH BAR</h2>
        </nav>
    </header>

    <div class="format">
        <br><br>
        <form>
            Sélectionner la couleur : 
            <select name="color">
                <option value="W">Blanc</option>
                <option value="R">Rouge</option>
                <option value="B">Noir</option>
                <option value="U">Bleu</option>
                <option value="G">Vert</option>
            </select>
        </br>
        </form>
    </div>

    <div class="icon_display">
        <div id="white"class="icon_deck"><img src="../images/icon_white.png" alt="" width="65" height="68" /></div>
        <div id="red" class="icon_deck"><img src="../images/icon_red.png" alt="" width="65" height="68" /></div>
        <div class="icon_deck"><img src="../images/icon_black.png" alt="" width="65" height="68" /></div>
        <div class="icon_deck"><img src="../images/icon_blue.png" alt="" width="65" height="68" /></div>
        <div class="icon_deck"><img src="../images/icon_green.png" alt="" width="65" height="68" /></div>
    </div>


    <!-- affichage des cartes via script_display.js-->
        <div id="zone">
        
    </div>


</body>
</html>