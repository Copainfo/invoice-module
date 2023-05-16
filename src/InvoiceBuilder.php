<?php
declare(strict_types=1);

namespace CodingpairQtn\InvoiceModule;

use DateTime;
use RuntimeException;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Classe qui permet la création des
 */
class InvoiceBuilder
{

    private InvoiceTemplateChecker $templateChecker;
    private DetailsTableInterface $detailsTableMaker;
    private ?string $currentTemplate = null;
    private string|false $html;
    /**
     * @var array|string[]
     */
    private array $issuerInfo;
    /**
     * @var array|string[]
     */
    private array $customerInfo;

    /**
     * @var array|string[]
     */
    private array $invoiceInfo;

    /**
     * @param DetailsTableInterface $detailsTableMaker
     */
    public function __construct(DetailsTableInterface $detailsTableMaker)
    {
        $this->templateChecker = new InvoiceTemplateChecker();
        $this->detailsTableMaker = $detailsTableMaker;
    }

    /**
     * @param string $template
     *
     */
    private function initTemplate(string $template): void
    {
        $this->templateChecker->checkTemplateFile($template);
        if ($this->currentTemplate === $template) {
            return;
        }
        $this->currentTemplate = $template;
        $templateContent= file_get_contents("./template/invoice/" . $this->currentTemplate . "." . $this->templateChecker->getFilesType());
        if ($templateContent === false) {
            throw new RuntimeException("Le fichier template/invoice/" . $template . ".html/php n'existe pas");
        }
        $this->html = $templateContent ;
    }

    /**
     * @param array<string, mixed> $data
     * @return string
     */
    private function setAllParamInTemplate(array $data): string
    {
        $pattern = '[[%s]]';
        $map = [];
        // recuperation du tableau et des infos pour les totaux
        $finalData= $this->makeFinalParamData($data);
        foreach ($finalData as $var => $value) {
            $var = strtoupper($var);
            $map[sprintf($pattern, $var)] = strip_tags($value, [
                "table",
                "colgroup",
                "thead",
                "tr",
                "td",
                "th",
                "col",
                "tbody"
            ]);
        }
        $str = strtr($this->html, $map);
        $this->detectForgottenProperties($str);
        return $str;
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return string
     * @throws Html2PdfException
     */
    public function getInvoice(string $template, array $data):string
    {
        $this->initTemplate($template);
        $htmlCompletedInvoice = $this->setAllParamInTemplate($data);
        return $this->getPdfInvoice($htmlCompletedInvoice);
    }


    /**
     * Fonction qui permet de mettre en place toutes les variables nécessaires pour la creation de l'émetteur de la facture
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $phone
     * @param string $siret
     * @param string $type
     * @param string $capital
     * @param string $cityImat
     * @param string $RCS
     * @param string $tvaNumber
     * @return void
     */
    public function setIssuerInfo(
        string $name,
        string $address,
        string $city,
        string $phone,
        string $siret,
        string $type,
        string $capital,
        string $cityImat,
        string $RCS,
        string $tvaNumber
    ): void {
        $this->issuerInfo = [
            "ISSUERNAME"=>$name,
            "ISSUERADRESS"=>$address,
            "ISSUERCITY"=>$city,
            "ISSUERPHONE"=>$phone,
            "ISSUERSIRET"=>$siret,
            "ISSUERTYPE"=>$type,
            "ISSUERCAPITAL"=>$capital,
            "ISSUERCITYIMMAT"=>$cityImat,
            "ISSUERRCS"=>$RCS,
            "ISSUERTVANUMBER"=>$tvaNumber,
        ];
    }

    /**
     * Fonction qui permet de mettre en place toutes les variables nécessaires pour la creation du client de la facture
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $phone
     * @param string $mail
     * @return void
     */
    public function setCustomerInfo(
        string $name,
        string $address,
        string $city,
        string $phone,
        string $mail,
    ): void {
        $this->customerInfo = [
            "CUSTOMERNAME"=>$name,
            "CUSTOMERADRESS"=>$address,
            "CUSTOMERCITY"=>$city,
            "CUSTOMERMAIL"=>$mail,
            "CUSTOMERPHONE"=>$phone,
        ];
    }

    /**
     * Fonction qui sert à mettre en place les infos de la facture
     * @param string $nbInvoice
     * @param string $subject
     * @param DateTime $dateTime
     * @return void
     */
    public function setInvoiceInfo(
        string $nbInvoice,
        string $subject,
        DateTime $dateTime,
    ):void {
        $this->invoiceInfo = [
            "NBINVOICE"=>$nbInvoice,
            "SUBJECT"=>$subject,
            "DATE"=>$dateTime->format("d/m/Y"),
        ];
    }

    /**
     * @param string $str
     */
    private function detectForgottenProperties(string $str): void
    {
        $res = preg_match("/\[\[\w+]]/", $str);
        if ($res >= 1) {
            throw new RuntimeException("Un paramètre a été oublier dans le template : " . $this->currentTemplate);
        }
    }

    /**
     * @param string $htmlCompletedInvoice
     * @return string
     * @throws Html2PdfException
     */
    private function getPdfInvoice(string $htmlCompletedInvoice): string
    {
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($htmlCompletedInvoice);
        return $html2pdf->output('invoice', "S");
    }

    /**
     * Fonction qui regroupe tous les params de la facture
     * @param array<string,string> $data
     * @return array|string[]
     */
    private function makeFinalParamData(array $data): array
    {
        $allData = array_merge($this->issuerInfo, $this->customerInfo, $this->invoiceInfo, $data);
        $allData["TOTALHT"]= $this->detailsTableMaker->getTotalHT();
        $allData["TOTALTVA"]= $this->detailsTableMaker->getTotalTVA();
        $allData["TOTALTTC"]= $this->detailsTableMaker->getTotalTTC();
        $allData["INVOICEDETAILTABLE"]= $this->detailsTableMaker->makeProductTable();
        return $allData;
    }
}
