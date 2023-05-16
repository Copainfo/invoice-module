<?php
declare(strict_types=1);

namespace CodingpairQtn\test;

use CodingpairQtn\InvoiceModule\InvoiceTemplateChecker;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Classe de test pour les fonctions de verification
 */
class InvoiceTemplateCheckerTest extends TestCase
{

    public function testTemplateFilesNotExist():void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Le dossier template/invoice n'existe pas");
        $templateChecker = new InvoiceTemplateChecker();
        chdir(__DIR__. "/testFiles");
        $templateChecker->checkTemplateFile("testTemplate");
    }

    public function testTemplateFilesPhpHtmlNotExist():void
    {

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Le fichier template/invoice/testTemplate.html/php n'existe pas");
        $templateChecker = new InvoiceTemplateChecker();
        chdir(__DIR__. "/TestNoTemplate");
        $templateChecker->checkTemplateFile("testTemplate");
    }


    public function testTemplateFilesPhpHtmlExistValidParam():void
    {
        $templateChecker = new InvoiceTemplateChecker();
        chdir(__DIR__. "/testTemplate");
        $return = $templateChecker->checkTemplateFile("TestTemplate");
        self::assertTrue($return);
    }

    public function testTemplateFilesPhpGetFileType():void
    {
        $templateChecker = new InvoiceTemplateChecker();
        chdir(__DIR__. "/testTemplate");
        $return = $templateChecker->checkTemplateFile("TestTemplate");
        self::assertTrue($return);
        self::assertEquals(InvoiceTemplateChecker::PHP_FILES, $templateChecker->getFilesType());
    }
}
