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
class DetailsTableExempleTest extends TestCase
{

    private Generator $fake;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fake = Factory::create('fr_FR');
    }

    /**
     * @return void
     * @throws Exception
     * @throws Html2PdfException
     */
    public function testGetInvoice(): void
    {
        chdir(__DIR__ . "/testTemplate");
        $detail = new DetailsTableExemple();
        $builder = new InvoiceBuilder($detail);
        $detail->setProductList([
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
        $builder->setInvoiceInfo("1", "Test", new DateTime());
        $builder->setIssuerInfo(
            $this->fake->name,
            $this->fake->streetAddress,
            $this->fake->postcode . " " . $this->fake->city,
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
            $this->fake->postcode . " " . $this->fake->city,
            $this->fake->e164PhoneNumber,
            $this->fake->email,
        );
        $invoicePdf = $builder->getInvoice("TestTemplate", ["LOGOSRC" => "./logo.png"]);
        # Chemin vers fichier texte
        $file = "testDetails.pdf";
        # Ouverture en mode écriture
        $fileopen = (fopen("$file", 'a'));
        # Ecriture de "Début du fichier" dansle fichier texte
        fwrite($fileopen, $invoicePdf);
        # On ferme le fichier proprement
        fclose($fileopen);
        self::assertIsString($invoicePdf);
    }
}
