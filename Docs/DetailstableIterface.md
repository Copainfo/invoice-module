# Interface Détail Table

Pour pouvoir personnaliser au maximum la facture le tableau de détails. La classe invoiceBuilder a besoin d'un DetailInvoiceInterface.

Cette interface a pour but de permettre au builder de récupérer un tableau qui contient les details de la facture.

Ainsi que les differents totaux.

L'interface a besoin de 4 fonctions : 

```PHP
    /**
     * Fonction qui doit retourner le tableau des details en HTML4.01
     * @return string
     */
    public function makeProductTable():string;

    /**
     * Fonction qui doit retourner le total hors taxe de la facture
     * @return string
     */
    public function getTotalHT():string;

    /**
     * Fonction qui doit retourner le total de TVA de la facture
     * @return string
     */
    public function getTotalTVA():string;

    /**
     * Fonction qui doit retourner le total TTC de la facture
     * @return string
     */
    public function getTotalTTC():string;
```

Exemple d'interface : [Exemple](../src/DetailsTableExemple.php)
