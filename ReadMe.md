# InvoiceModule

InvoiceModule est un générateur de facture au format PDF.

Ce générateur est en php **8.1**.

Il s'appuie sur la librairie [Html2Pdf](https://github.com/spipu/html2pdf) qui permet de faire la conversion d'un text en HTML 4.01 en Pdf.


## Configuration requise

InvoiceModule fonctionne avec PHP >8.1 et Composer.

Vous aurez également besoin d'au moins les extensions php suivantes :

* gd
* mbstring

## Installation

Vous devez utiliser Composer pour installer InvoiceModule.

Si vous ne savez pas ce qu'est Composer :

 * Vous pouvez trouver la documentation sur https://getcomposer.org/doc/

 * Vous pouvez trouver tous les paquets disponibles sur https://packagist.org/

```bash
composer require copainfo/invoice-module
```

## Utilisation 

Pour la customisation des Factures il y a besoin de deux modifications de votre part : 

* [Les templates](./Docs/Template.md)
* [Le detailsTableInterface](./Docs/DetailstableIterface.md)

## Exemple

### Template 
Un exemple de template est disponible ici : [Template](./exemple/ExempleTemplate.php)

Ce template génère une facture sans paramètre ressemble à ce fichier : [Pdf](./exemple/ExampleTemplateRender.pdf)

### Interface
Un exemple d'interface est disponible ici : [Interface](./src/DetailsTableExemple.php)

Cette interface génère une facture qui ressemble à ce fichier : [Pdf](./exemple/ExempleWithInterface.pdf)

### Utilisation

Exemple d'utilisation avec l'interface d'exemple.

```PHP
$detail = new DetailsTableExemple(); // On crée un Objet Qui implement l'interface
$builder = new InvoiceBuilder($detail); // On crée un invoiceBuilder
$detail->setProductList([ // On ajoute des produits au tableau des produits
    [
        "product"=>"Product 1",
        "description"=>"first Product",
        "PUHT"=>100,
        "TVA"=>20,
        "quantity"=>2,
        ],
    [
        "product"=>"Product 2",
        "description"=>"next Product",
        "PUHT"=>100,
        "TVA"=>20,
        "quantity"=>2,
    ]
]);
$builder->setInvoiceInfo("1", "Test", new DateTime());  // On donne les informations de la facture
$builder->setIssuerInfo( // On donne les informations de l'entreprise
    "Nom de l'entreprise",
    "25 rue de l'adresse ",
    "65214 VilleIci",
    "06 06 06 06 06",
    "900 367 004 00653",
    "SARL",
    "1000 €",
    "VilleIci",
    "900 367 004 00653",
    "900 367 004 00653"
);
$builder->setCustomerInfo(// On donne les informations du client
    "Nom du client",
    "65 rue de l'adresse",
    "65214 VilleIci",
    "06 06 06 06 06",
    "mail@mail.com",
);
$invoicePdf = $builder->getInvoice( // Ici, on crée la facture 
                        "TestTemplate", // avec le template : TestTemplate.
                        ["LOGOSRC" => "./logo.png"]  // on donne les valeurs des paramètres personnalisés 
                        );
```

La fonction méthode getInvoice permet de retourner le contenu du fichier PDF. 
