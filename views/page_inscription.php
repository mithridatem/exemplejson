<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../HTML_CSS/page_inscription.css" />
    <title>Subscribe</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <h1>LOGO</h1>
            <div class="titreSearch">SEARCH BAR</div>
            <div class="searchBar">
                <input type="text">
            </div>
            <ul>
                <li><a href="page_signIn">Bibliothèque</a> </li>
                <li><a href="page_decks">Decks</a></li>
            </ul>
            <div class="logoMonCompte"><img src="../Images/icon_mon_compte.png" alt="" width="40px" height="40px" />
            </div>
            <div class="monCompte"><a href="../HTML_CSS/page_login.html">Mon Compte</a>
            </div>
        </nav>
    </header>


    <div class="blocInscription">
        <div class="box">
            <form action="" class="formBloc">

                <h3><strong>Inscrivez-vous</strong></h3>

                <div class="formGroupe">
                    <label for="utilisateur"><strong>Pseudo</strong></label>
                    <input type="text" name="login" id="utilisateur" required maxlength="16">
                </div>

                <div class="formGroupe">
                    <label for="mdp"><strong>Mot de passe</strong></label>
                    <input type="password" name="mdp" id="mdp" required maxlength="16">
                </div>

                <div class="formGroupe">
                    <label for="mdp"><strong>E-mail</strong></label>
                    <input type="text" name="mdp" id="mdp" required maxlength="16">
                </div>


                <div class="formGroupe">
                    <input type="submit" value="SUBSCRIBE" class="buttonSub">
                </div>

                <h4><strong>Suivez-nous</strong></h4>

                <div class="formGroupe">
                    <div class="iconDeck"><img src="../Images/logo_rs.png" alt="" width="60%" height="60%" /></div>
                </div>
            </form>
        </div>


        <script>
            const inputs = document.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {

                let field = inputs[i];

                field.addEventListener('input', (e) => {

                    if (e.target.value != "") {
                        e.target.parentNode.classList.add('animation');
                    } else if (e.target.value == "") {
                        e.target.parentNode.classList.remove('animation');
                    }

                })
            }
        </script>

</body>

</html>