<?php
declare(strict_types=1);

namespace CodingpairQtn\InvoiceModule;

use RuntimeException;

/**
 * Classe qui sert à verifier que les templates pour les factures contienne bien tous les variables nécessaires
 */
class InvoiceTemplateChecker
{
    public const HTML_FILES = "html";
    public const PHP_FILES = "php";
    /**
     * Indique l'extension du fichier de template
     * @var string
     */
    private string $filesType;
    private string $template;


    /**
     * Fonction qui indique si le template contient bien toutes les variables nécessaires qui sont :
     * - NBINVOICE : n° de facture
     * - SUBJECT : Objet de la facture
     * - DATE : date de creation de la facture
     * - ISSUERNAME : Nom de l'émetteur de la facture
     * - ISSUERADRESS: adresse de l'émetteur de la facture
     * - ISSUERCITY: ville et code postal de l'émetteur de la facture
     * - ISSUERPHONE: téléphone de l'émetteur de la facture
     * - ISSUERSIRET: Numéros de siret de l'émetteur de la facture
     * - ISSUERTYPE: Structure de l'émetteur de la facture
     * - ISSUERCAPITAL: Capital Social de l'émetteur de la facture
     * - ISSUERCITYIMMAT : Ville d'immatriculation de l'émetteur de la facture
     * - ISSUERRCS : numéros RCS de l'émetteur de la facture
     * - ISSUERTVANUMBER : numéros de TVA de l'émetteur de la facture
     *
     * - CUSTOMERNAME: Nom du client de la facture
     * - CUSTOMERADRESS: Adresse du client de la facture
     * - CUSTOMERCITY: viles et code postal du client de la facture
     * - CUSTOMERMAIL: email du client de la facture
     * - CUSTOMERPHONE: téléphone du client de la facture
     *
     * - INVOICEDETAILTABLE : Parametre qui serra utilisé pour placer les details de la facture
     * - TOTALHT : Total hors taxe de la facture
     * - TOTALTVA : Total de la TVA de la facture
     * - TOTALTTC : Total TTC de la facture
     *
     *
     */
    private function checkAllParam():void
    {
        $requiredParam = [
        "NBINVOICE",
        "SUBJECT",
        "DATE",
        "ISSUERNAME",
        "ISSUERADRESS",
        "ISSUERCITY",
        "ISSUERPHONE",
        "ISSUERSIRET",
        "ISSUERTYPE",
        "ISSUERCAPITAL",
        "ISSUERCITYIMMAT",
        "ISSUERRCS",
        "ISSUERTVANUMBER",
        "CUSTOMERNAME",
        "CUSTOMERADRESS",
        "CUSTOMERCITY",
        "CUSTOMERMAIL",
        "CUSTOMERPHONE",
        "INVOICEDETAILTABLE",
        "TOTALHT",
        "TOTALTVA",
        "TOTALTTC"
        ];
        $templateContent = file_get_contents("./template/invoice/" . $this->template . "." . $this->filesType);
        foreach ($requiredParam as $paramName) {
            $ressultTab[$paramName]=str_contains($templateContent, "[[$paramName]]");
        }
        $isMissingParam = in_array(false, $ressultTab);
        if ($isMissingParam) {
            $missingParam = array_filter(
                $ressultTab,
                function (bool $value) {
                    return $value === false;
                }
            );
            throw new RuntimeException($this->makeMissingErrorMessage($missingParam));
        }
    }

    /**
     * Fonction qui vérifie si le fichier existe et s'il est contient les params nécessaire On vérifie le fichier en HTML et en PHP.
     * @param string $template
     * @return bool
     */
    public function checkTemplateFile(string $template):bool
    {
        $this->template = $template;
        if (!is_dir("./template/invoice/")) {
            throw new RuntimeException("Le dossier template/invoice n'existe pas");
        }
        $isHtmlFiles = is_file("./template/invoice/" . $template . ".html");
        $isPhpFiles = is_file("./template/invoice/" . $template . ".php");
        // on vérifie l'existence des fichiers
        if (!($isHtmlFiles || $isPhpFiles)) {
            throw new RuntimeException("Le fichier template/invoice/" . $template . ".html/php n'existe pas");
        }
        $this->filesType  = $isPhpFiles ? self::PHP_FILES:self::HTML_FILES;
        $this->checkAllParam();
        return true;
    }

    /**
     * @param array<string,bool> $missingParam
     * @return string
     */
    private function makeMissingErrorMessage(array $missingParam):string
    {
        $message= "Le template: ".$this->template . " , ne contient pas tout les paramètres obligatoires les paramètres suivant ne sont pas present :";
        foreach ($missingParam as $param => $value) {
            $message.= " $param ,";
        }
        return $message;
    }

    /**
     * @return string
     */
    public function getFilesType(): string
    {
        return $this->filesType;
    }
}
