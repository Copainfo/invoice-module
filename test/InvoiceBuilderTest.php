<?php
declare(strict_types=1);

namespace CodingpairQtn\InvoiceModule;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Spipu\Html2Pdf\Exception\Html2PdfException;

/**
 * Class de test
 */
class InvoiceBuilderTest extends TestCase
{

    private Generator $fake;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fake = Factory::create('fr_FR');
    }

    /**
     * @throws Exception
     */
    public function test__construct():void
    {
        $detail=$this->createMock(DetailsTableInterface::class);
        $builder = new InvoiceBuilder($detail);
        self::assertInstanceOf(InvoiceBuilder::class, $builder);
    }

    /**
     * @return void
     * @throws Exception
     * @throws Html2PdfException
     */
    public function testGetInvoice():void
    {
        chdir(__DIR__. "/testTemplate");
        $detail=$this->createMock(DetailsTableInterface::class);
        $builder = new InvoiceBuilder($detail);
        $detail->method("getTotalHT")->willReturn("300,00 €");
        $detail->method("getTotalTVA")->willReturn("200,00 €");
        $detail->method("getTotalTTC")->willReturn("500,00 €");
        $detail->method("makeProductTable")->willReturn(
            <<<HTML
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
        <tr>

            <td valign="top" style="font-size:12px;">mon produit</td>
            <td valign="top" style="font-size:12px;">C'est mon produit</td>
            <td valign="top" style="font-size:12px;">60,00 </td>
            <td valign="top" style="font-size:12px;">20% </td>
            <td valign="top" style="font-size:12px;">3 </td>
            <td valign="top" style="font-size:12px;">180,00 </td>

        </tr>
        <tr>

            <td valign="top" style="font-size:12px;">mon service</td>
            <td valign="top" style="font-size:12px;">C'est mon service</td>
            <td valign="top" style="font-size:12px;">120,00 </td>
            <td valign="top" style="font-size:12px;">20% </td>
            <td valign="top" style="font-size:12px;">1 </td>
            <td valign="top" style="font-size:12px;">120,00 </td>

        </tr>
        </tbody>
    </table>
HTML
        );
        $builder->setInvoiceInfo("1", "Test", new DateTime());
        $builder->setIssuerInfo(
            $this->fake->name,
            $this->fake->streetAddress,
            $this->fake->postcode." ".$this->fake->city,
            $this->fake->e164PhoneNumber,
            "900 367 004 00653",
            $this->fake->companySuffix,
            (string)$this->fake->numberBetween(1000, 10000),
            $this->fake->city,
            "900 367 004 00653",
            "900 367 004 00653"
        );
        $builder->setCustomerInfo(
            $this->fake->name,
            $this->fake->streetAddress,
            $this->fake->postcode." ".$this->fake->city,
            $this->fake->e164PhoneNumber,
            $this->fake->email,
        );
        $invoicePdf = $builder->getInvoice("TestTemplate", ["LOGOSRC"=>"./logo.png"]);
        # Chemin vers fichier texte
        $file ="test.pdf";
# Ouverture en mode écriture
        $fileopen=(fopen("$file", 'a'));
# Ecriture de "Début du fichier" dansle fichier texte
        fwrite($fileopen, $invoicePdf);
# On ferme le fichier proprement
        fclose($fileopen);
        self::assertIsString($invoicePdf);
    }
}
