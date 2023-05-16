
<page style="font-size:12px;color: #333333;background-color:#FFFFFF;width: 100%">
    <style type="text/css">
        page{
            --main-color: #f2f2f2
        }

        table {
            color: #717375;
            line-height: 5mm;
            border-collapse: collapse;
        }

        .infoFacture {
            padding-bottom:100px;
        }

        .totalTable {
            background: var(--main-color);
        }

        img {
            height: 200px;
        }

        h4{
            color: #000000;
        }
        h5 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 5px;
            color: #000000;
        }

        .border th {
            border: 1px solid #000;
            color: white;
            background: #000;
            padding: 5px;
            font-weight: normal;
            font-size: 14px;
            text-align: center;
        }

        .border td {
            border: 1px solid #CFD1D2;
            padding: 5px 10px;
            text-align: center;
        }

    </style>

    <table>
        <tr>
            <td style="width: 70%">
                <div class="logo"><img src="[[LOGOSRC]]"  height="200"></div>
            </td>
            <td>
                <h4>Date: [[DATE]]</h4>
            </td>
        </tr>
    </table>
    <table class="infoFacture">
        <tbody>
        <tr >
            <td class="emmetteur" style="width: 50%">
                <h4>Vendeur : </h4>
                <p valign="top"  style="font-size:12px;">
                    <strong>[[ISSUERNAME]]</strong><br>
                    [[ISSUERADRESS]] <br>
                    [[ISSUERCITY]] <br>
                    [[ISSUERPHONE]] <br>
                    SIRET: [[ISSUERSIRET]]<br>
                </p>

            </td>
            <td class="destinataire" style="width: 50%">
                <h4>Destinataire : </h4>
                <p valign="top"  style="font-size:12px;">
                    <strong>[[CUSTOMERNAME]]</strong><br>
                    [[CUSTOMERADRESS]] <br>
                    [[CUSTOMERCITY]] <br>
                    [[CUSTOMERMAIL]] <br>
                    [[CUSTOMERPHONE]] <br>

                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <h5>Facture n° [[NBINVOICE]]</h5>
    <h4>Objet : [[SUBJECT]] </h4>


    [[INVOICEDETAILTABLE]]

    <table  style="padding-left: 550px;" cellspacing="0" cellpadding="2" border="1px">
        <tbody>
        <tr>
            <td class="totalTable" bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px; padding: 10px 15px 10px 5px;">Total HT </td>
            <td style="font-size:12px; padding: 10px 15px 10px 5px;">[[TOTALHT]]</td>
        </tr>
        <tr >

            <td class="totalTable" bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px; padding: 10px 15px 10px 5px;"><b>Total TVA</b></td>
            <td style="font-size:12px; padding: 10px 15px 10px 5px;"><b>[[TOTALTVA]]</b></td>
        </tr>
        <tr >

            <td class="totalTable" bordercolor="#ccc" bgcolor="#f2f2f2" align="left" style="font-size:12px; padding: 10px 15px 10px 5px;"><b>Total TTC</b></td>
            <td style="font-size:12px; padding: 10px 15px 10px 5px;"><b>[[TOTALTTC]]</b></td>
        </tr>

        </tbody>
    </table>

    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 100%; text-align: center;">
                    [[ISSUERNAME]], [[ISSUERTYPE]] au capital de [[ISSUERCAPITAL]] - RCS [[ISSUERCITYIMMAT]] [[ISSUERRCS]]
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center;">
                    N° TVA :[[ISSUERTVANUMBER]]
                </td>
            </tr>
        </table>

    </page_footer>
</page>