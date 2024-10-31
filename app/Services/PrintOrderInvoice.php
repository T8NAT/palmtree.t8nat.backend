<?php namespace App\Services;

use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\Order;
class PrintOrderInvoice {

    public static function printOrder($unique_id,$type = 'I'){
        $order = Order::where('unique_id',$unique_id)->first();
        $view    = \View::make('pdf.order',compact('order'));
        $html    = $view->render();
        $pdf     = new TCPDF();
        $pdf::SetTitle('فاتورة-رقم-'.$unique_id);
        $pdf::AddPage();
        $pdf::setRTL(true);
        $pdf::SetFont('aealarabiya', '', 14,'',false);
        $pdf::writeHTMLCell(0,0,'','',$html,'', 1, 0, true, 'R', false);
        $pdf::setPrintFooter(false);
        $pdf::setPrintHeader(false);
        $pdf::SetMargins(0,0,0);
        $pdf::setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );
        $pdf::Output(public_path('orders_invoices/'.'بوليصة-رقم-'.$unique_id.'.pdf'),$type);
    }
}