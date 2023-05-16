<?php
declare(strict_types=1);

namespace CodingpairQtn\InvoiceModule;

/**
 * Interface qui sert à mettre en place le tableau qui contient le detail des produits
 */
interface DetailsTableInterface
{

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
}
