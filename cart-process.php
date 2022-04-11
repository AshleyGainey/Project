<?php   
session_start();

if(!empty($_SESSION['shopping_cart'])){  
    $total = 0;  

    foreach($_SESSION['shopping_cart'] as $key => $row){ 

    $output .='<a href="?action=delete&id='.$row['id'].'" class="action-icon close-shopping-cart-box"><i class="ti ti-close"></i></a>';
    $output .='<span>'.$row['titel'].'</span><span class="text-muted"> x '.$row['quantity'].'</span><br /> ';


    $total = $total + ($row['quantity'] * $row['prijs']);  
    }  

    $output.='<br />Totaal:&euro; '.number_format($total, 2).'';



if (isset($_SESSION['shopping_cart'])){
if (count($_SESSION['shopping_cart']) > 0){

    $output.='<a href="checkout-cash" style="margin-top:20px;" class="btn btn-outline-warning">Cash payment</a>';
    $output.='<a href="checkout-paypal" style="margin-top:20px;" class="btn btn-outline-warning">Paypal payment</a>';

 }}
}
?>
