<?php

namespace App\Service\Pdf;


use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Commande;
use Twig\Environment;

class PdfService
{

    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->domPdf = new DomPdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Garamond');
        $pdfOptions->setIsRemoteEnabled(true);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $this->domPdf->setHttpContext($context);
        $this->domPdf->setOptions($pdfOptions);
        $this->domPdf->setPaper('A4', 'portrait');
    }

    public function userCommandePdf ($html)
    {

        $this->domPdf->loadHtml($html);
        $this->domPdf->render();

        // On génère un nom de fichier
        $fichier = 'user-commande-.pdf';

        // On envoie le PDF au navigateur
        $this->domPdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }


    public function showPdfFile($html) {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("details.pdf", [
            'Attachement' => true
        ]);
    }

    public function generateBinaryPDF($html) {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output();
    }

    public function indexAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        
        $html = $this->twig->render('sandboxBundle:Default:template.html.twig', array(
            //..Send some data to your view if you need to //
        ));
        
        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
}
