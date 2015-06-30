<?php
namespace PP\Common;

require_once dirname(__DIR__) . '/library/tcpdf/tcpdf.php'; 


class SimplePDFGen
{
    public static function Generate($data, $properties=array(), $filepath="")
    {
        if (!is_array($data)) {
            return '';
        }
        if (count($data)==0) {
            return '';
        }
        
        $defaults = array(
            "Orientation" => 'P',
            "Unit" =>'mm',
            "Size" => 'A4',
            "Creator" => 'Coversure',
            "Author" => 'Coversure',
            "Keyword" => 'Coversure',
            "Subject" => 'Coversure',
            "Title" => 'Coversure',
            "ActionDeny" => array('modify'),
            "TextStyle" => array(
                "size" => 10, //in pt
                "align" => "L",  // L: left, C: center, R: right, J: justification
                "valign" => "T", // T: TOP, M: middle, B: bottom
                "style" => "", //Empty: regular;  B: bold I: italic U: underline D: line through O: overline
                "family" => "cid0jp", //cid0jp, msungstdlight, cid0ct can display Chinese //helvetica
                "border"=>0, //0: hide; 1: show
                "color" => array(
                    "R"=>0,
                    "G"=>0,
                    "B"=>0
                ),
            ),
            "outpath"=>$filepath, //output file path
        );
        $PDFSETTING = array_replace_recursive($defaults, $properties);

        $PDFGen = new \TCPDF($PDFSETTING['Orientation'], $PDFSETTING['Unit'], $PDFSETTING['Size'], true, 'UTF-8', false, false);
        $PDFGen->SetCreator($PDFSETTING['Creator']);
        $PDFGen->SetAuthor($PDFSETTING['Author']);
        $PDFGen->SetKeywords($PDFSETTING['Keyword']);
        $PDFGen->SetSubject($PDFSETTING['Subject']);
        $PDFGen->SetTitle($PDFSETTING['Title']);
        $PDFGen->SetProtection($PDFSETTING['ActionDeny'], '', null, 3, null);
        $PDFGen->setPrintFooter(false);
        $PDFGen->setPrintHeader(false);
        $PDFGen->SetMargins(0, 0, 0);
        $PDFGen->SetAutoPageBreak(false, 0);
        $PDFGen->setImageScale(4.16); // Image DPI / Doc DPI (72)
        $PDFGen->setJPEGQuality(90);
        $PDFGen->SetCompression(true);
        
        //$fontname = \TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'] . '/OpenSans-Regular.ttf', '', '', 32);
        
        foreach ($data as $dPage) {
            $PDFGen->addPage();
            if (file_exists($dPage['src'])) {
                $PDFGen->Image($dPage['src'], 0, 0, 0, 0, 'JPG', '', 'T', false, 300, 'L', false, false, 0, false, false, false, false, array());
            }
            $PDFGen->setPageMark();
            
            foreach ($dPage['data'] as $field) {
                if (!empty($field['text'])) {
                    $finalData = array_replace_recursive(array("style"=>$PDFSETTING['TextStyle']), $field);
                    $PDFGen->SetTextColor($finalData['style']['color']['R'], $finalData['style']['color']['G'], $finalData['style']['color']['B']);
                    $PDFGen->SetFont($finalData['style']['family'], $finalData['style']['style'], $finalData['style']['size']);
                    $PDFGen->MultiCell($finalData['width'], $finalData['height'], $finalData['text'], $finalData['style']['border'], $finalData['style']['align'], false, 1, $finalData['x'], $finalData['y'], true, 0, false, false, 0, $finalData['style']['valign'], true);
                } elseif (!empty($field['link'])) {
                    $PDFGen->Link($field['x'], $field['y'], $field['width'], $field['height'], $field['link']);
                }
            }
        }
        /*
        header('Content-Type: application/pdf');
        echo $PDFGen->Output($PDFSETTING['outpath'],'S');
        flush();
        exit();
        */
        if (empty($PDFSETTING['outpath'])) {
            return $PDFGen->Output($PDFSETTING['outpath'], 'S');
        } //I: Directly to browser; F: Save to file; S: String
        else {
            $PDFGen->Output($PDFSETTING['outpath'], 'F');
        } //I: Directly to browser; F: Save to file; S: String
    }
}
