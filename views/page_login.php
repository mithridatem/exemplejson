<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../HTML_CSS/page_login.css" />
    <title>Login</title>
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
            <div class="monCompte">Mon Compte
            </div>
        </nav>
    </header>


    <div class="blocInscription">
        <div class="box">
            <form action="" class="formBloc">

                <h3><strong>Identifiez-vous</strong></h3>

                <div class="formGroupe">
                    <label for="utilisateur"><strong>Utilisateur</strong></label>
                    <input type="text" name="login" id="utilisateur" required maxlength="16">
                </div>

                <div class="formGroupe">
                    <label for="mdp"><strong>Mot de passe</strong></label>
                    <input type="text" name="mdp" id="mdp" required maxlength="16">
                </div>

                <div class="formGroupe">
                    <input type="submit" value="LOGIN" class="buttonSub">
                </div>

                <div class="mdpPerdu">
                    <a href="#">Mot de passe oublié ?</a>
                    </br>
                    </br>
                    <a href="../HTML_CSS/page_inscription.html">Pas encore inscrit ?</a>
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