// C'est le fichier qu'on va appeler pour l'API
//Afficher la liste des catégories
//url api
const url = '../API/api_cards.php?ALL'; //on va stocker l'adresse qui récupère toutes les cartes (api_cards.php)
const urlW = '../API/api_cards.php?white'; //on va stocker l'adresse qui récupère toutes les cartes (api_cards.php)
const urlR = '../API/api_cards.php?red'; //on va stocker l'adresse qui récupère toutes les cartes (api_cards.php)

//zone pour afficher le contenu de l'api
let zone = document.querySelector('#zone'); // document = page html (DOM) ; querySelector permet de récupérer du contenu html ciblé par le CSS cf. page_decks.php
//fonction récupération et affichage du json dans la page

let white = document.querySelector('#white');
let red = document.querySelector('#red'); // On récupère l'élément html de l'id red 

async function showCardsApi() {
    zone.innerHTML = "";
    // On stock l'url de l'API json 
    const data = await fetch(url); //await = permet d'attendre le chargement de la page
    // On va stocker le json
    const json = await data.json(); //.json permet d'instancier un tableau avec la variable
    // console.log(json)

    let url_image; //On crée une variable qui va stocker l'id de l'img (urlImage -> bdd)

    // On a crée une boucle pour parcourir tout le fichier json
    for (let i in json) {
        // On va stocker l'id de l'image à afficher
        url_image = json[i].urlImage;
        // afficher les images dans zone
        zone.innerHTML += "<img src=https://api.scryfall.com/cards/" + url_image + "?format=image\ height=\"30%\" width=\"10%\"></img >";
        //innerHTML va ajouter le contenu de la balise à l'intérieur de la div "zone" cf. page_decks.php
    }
}

async function showWhiteCards() {
    zone.innerHTML = "";
    // On stock l'url de l'API json 
    const data = await fetch(urlW); //await = permet d'attendre le chargement de la page
    // On va stocker le json
    const json = await data.json(); //.json permet d'instancier un tableau avec la variable
    // console.log(json)

    let url_image; //On crée une variable qui va stocker l'id de l'img (urlImage -> bdd)

    // On a crée une boucle pour parcourir tout le fichier json
    for (let i in json) {
        // On va stocker l'id de l'image à afficher
        url_image = json[i].urlImage;
        // afficher les images dans zone
        zone.innerHTML += "<img src=https://api.scryfall.com/cards/" + url_image + "?format=image\ height=\"30%\" width=\"10%\"></img >";
        //innerHTML va ajouter le contenu de la balise à l'intérieur de la div "zone" cf. page_decks.php
    }
}

async function showRedCards() {
    zone.innerHTML = "";
    // On stock l'url de l'API json 
    const data = await fetch(urlR); //await = permet d'attendre le chargement de la page
    // On va stocker le json
    const json = await data.json(); //.json permet d'instancier un tableau avec la variable
    // console.log(json)

    let url_image; //On crée une variable qui va stocker l'id de l'img (urlImage -> bdd)

    // On a crée une boucle pour parcourir tout le fichier json
    for (let i in json) {
        // On va stocker l'id de l'image à afficher
        url_image = json[i].urlImage;
        // afficher les images dans zone
        zone.innerHTML += "<img src=https://api.scryfall.com/cards/" + url_image + "?format=image\ height=\"30%\" width=\"10%\"></img >";
        //innerHTML va ajouter le contenu de la balise à l'intérieur de la div "zone" cf. page_decks.php
    }
}

white.addEventListener('click', showWhiteCards);
//white.addEventListener('click', showRedCards);

red.addEventListener('click', showRedCards);
//red.addEventListener('click', showWhiteCards);


showCardsApi();