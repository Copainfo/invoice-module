<?php
declare(strict_types=1);

namespace CodingpairQtn\InvoiceModule;

use RuntimeException;

/**
 * Class exemple pour la mise en place de l'interface DetailsTable
 */
class DetailsTableExemple implements \CodingpairQtn\InvoiceModule\DetailsTableInterface
{

    private float $totalHT= 0;
    private float $totalTTC= 0;
    private float $totalTVA= 0;
    /**
     * Liste des produits à mettre dans le tableau
     * @var array<array<string,mixed>>
     */
    private ?array $productList = null;

    /**
     * Fonction qui met en place la liste des items exemple :
     * "product" =>nom du produit,
     * "description" => description du produit,
     * "PUHT" => Prix Unitaire HT,
     * "TVA" → Taux de la TVA,
     * "quantity" → Quantité du produit,
     * @param array<array<string,mixed>> $productList
     * @return void
     */
    public function setProductList(array $productList):void
    {
        foreach ($productList as $product) {
            $this->checkProductType($product);
        }
        $this->productList=$productList;
    }

    /**
     * Fonction qui ajoute un item à la liste
     * @param string $product
     * @param string $description
     * @param float $PUHT
     * @param int $TVA
     * @param int $quantity
     * @return void
     */
    public function addProductItem(
        string $product,
        string $description,
        float $PUHT,
        int $TVA,
        int $quantity,
    ):void {
        $this->productList[]= [
            "product"=>$product,
            "description"=>$description,
            "PUHT"=>$PUHT,
            "TVA"=>$TVA,
            "quantity"=>$quantity,
        ];
    }

    /**
     * Fonction qui retourne la table des produits
     * @return string
     */
    public function makeProductTable(): string
    {
        if ($this->productList === null) {
            throw new RuntimeException("La liste des produit n'est pas correctement définie");
        }
        $tableDetails = <<<HTML
 <table class="ListeProduit" style="width: 100%; padding-bottom: 50px" cellspacing="0" cellpadding="2" border="1" bordercolor="#CCCCCC">
        <colgroup>
            <col style="width: 12%; text-align: left; padding: 5px 0; ">
            <col style="width: 40%; text-align: left; padding: 5px 0; ">
            <col style="width: 15%; text-align: right; padding: 5px 0; ">
            <col style="width: 10%; text-align: right; padding: 5px 0; ">
            <col style="width: 10%; text-align: center; padding: 5px 0; ">
            <col style="width: 13%; text-align: center; padding: 5px 0; ">
        </colgroup>
        <thead>
        <tr style="background: #E7E7E7;">
            <th style="border-bottom: solid 1px black;">Produit</th>
            <th style="border-bottom: solid 1px black;">Description</th>
            <th style="border-bottom: solid 1px black;">Prix Unitaire HT</th>
            <th style="border-bottom: solid 1px black;">TVA</th>
            <th style="border-bottom: solid 1px black;">Quantité</th>
            <th style="border-bottom: solid 1px black;">Prix Net</th>
        </tr>
        </thead>
        <tbody>
HTML;

        foreach ($this->productList as $product) {
            $priceNet=$product["quantity"]*$product["PUHT"];
            $priceTva= ($priceNet/100)*$product["TVA"];
            $priceTTC=$priceNet+$priceTva;
            $this->totalHT+= $priceNet;
            $this->totalTVA+= $priceTva;
            $this->totalTTC+= $priceTTC;

            $tableDetails .= <<<HTML
<tr>
    <td valign="top" style="font-size:12px;">{$product["product"]}</td>
    <td valign="top" style="font-size:12px;">{$product["description"]}</td>
    <td valign="top" style="font-size:12px;">{$product["PUHT"]} €</td>
    <td valign="top" style="font-size:12px;">{$product["TVA"]} %</td>
    <td valign="top" style="font-size:12px;">{$product["quantity"]} </td>
    <td valign="top" style="font-size:12px;">$priceNet €</td>

</tr>
HTML;
        }
        $tableDetails.=<<<HTML
</tbody>
</table>
HTML;
        return $tableDetails;
    }

    /**
     * @return string
     */
    public function getTotalHT(): string
    {
        return $this->totalHT."€";
    }

    /**
     * @return string
     */
    public function getTotalTVA(): string
    {
        return $this->totalTVA."€";
    }

    /**
     * @return string
     */
    public function getTotalTTC(): string
    {
        return $this->totalTTC."€";
    }

    /**
     * @param array<string, mixed> $product
     * @return void
     */
    private function checkProductType(array $product)
    {
        $listeKeyNeeded = [
            "product",
            "description",
            "PUHT",
            "TVA",
            "quantity",
        ];
        foreach ($product as $key => $value) {
            if (!in_array($key, $listeKeyNeeded)) {
                throw new RuntimeException("Le produit n'est pas correctement formater");
            }

            switch ($key) {
                case "product":
                case "description":
                    if (!is_string($value)) {
                        throw new RuntimeException("Le type de $key doit être un string mais c'est un ". getType($value));
                    }
                    break;
                case "PUHT":
                case "TVA":
                case "quantity":
                    if (!is_int($value)) {
                        throw new RuntimeException("Le type de $key doit être un number mais c'est un ". getType($value));
                    }
                    break;
            }
        }
    }
}
