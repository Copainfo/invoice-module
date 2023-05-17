# Template 


## Créer un Template

Pour que les facture puisse être générée, il faut mettre en place un template.

Ce template permet de personnaliser la sortie de la facture. 

Pour tous les éléments à utiliser je vous invite à consulter la documentation de [Html2Pdf](https://github.com/spipu/html2pdf/blob/master/doc/README.md)

Le Template doit être dans le repertoire ``./template/invoice`` à la racine de votre projet.

## Variable 

Pour remplir les informations nécessaires de la facture, les template doivent avoir un certain nombre de variables.

Les variables dans le template sont définis de la manière suivante : [[NOMDUPARAM]].

### Variable Obligatoire

Ce template doit contenir une liste de paramètre qui doivent apparaitre obligatoirement dans une facture en France.
Voici la liste des paramètres :

* NBINVOICE : n° de facture
* SUBJECT : Objet de la facture
* DATE : date de creation de la facture
* ISSUERNAME : Nom de l'émetteur de la facture
* ISSUERADRESS: adresse de l'émetteur de la facture
* ISSUERCITY: ville et code postal de l'émetteur de la facture
* ISSUERPHONE: téléphone de l'émetteur de la facture
* ISSUERSIRET: Numéros de siret de l'émetteur de la facture
* ISSUERTYPE: Structure de l'émetteur de la facture
* ISSUERCAPITAL: Capital Social de l'émetteur de la facture
* ISSUERCITYIMMAT : Ville d'immatriculation de l'émetteur de la facture
* ISSUERRCS : numéros RCS de l'émetteur de la facture
* ISSUERTVANUMBER : numéros de TVA de l'émetteur de la facture

* CUSTOMERNAME: Nom du client de la facture
* CUSTOMERADRESS: Adresse du client de la facture
* CUSTOMERCITY: viles et code postal du client de la facture
* CUSTOMERMAIL: email du client de la facture
* CUSTOMERPHONE: téléphone du client de la facture

* INVOICEDETAILTABLE : Parametre qui serra utilisé pour placer les details de la facture
* TOTALHT : Total hors taxe de la facture
* TOTALTVA : Total de la TVA de la facture
* TOTALTTC : Total TTC de la facture

Pour plus d'information : [Réglementation facture française](https://www.economie.gouv.fr/entreprises/factures-mentions-obligatoires)

Un template sans ses informations ne sera pas pris en charge.

### Variable Supplémentaire

Dans le template, il peut aussi y avoir des paramètres en plus pour les styles ou des informations nécessaires à votre facture.

Attention si l'une des variables n'as pas de valeur le template ne sera pas pris en charge.