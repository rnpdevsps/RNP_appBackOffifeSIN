<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\UtilityFacades;
use PixelPay\Sdk\Base\Response;
use PixelPay\Sdk\Models\Card;
use PixelPay\Sdk\Models\Billing;
use PixelPay\Sdk\Models\Item;
use PixelPay\Sdk\Models\Order;
use PixelPay\Sdk\Requests\SaleTransaction;
use PixelPay\Sdk\Services\Transaction;
use PixelPay\Sdk\Entities\TransactionResult;
use PixelPay\Sdk\Models\Settings;
use App\Models\User;
use PixelPay\Sdk\Base\Helpers;

use Artisan;
use Session;

class PaymentController extends Controller
{

    public function formpaymentIntegration()
    {
        $id = \Auth::user()->id;
        $user = User::find($id);
        $paymentType = [];
        $paymentType[''] = 'Seleccionar Metodo de Pago';

        if (UtilityFacades::getsettings('pixelpaysetting') == 'on') {
            $paymentType['pixelpay'] = 'PixelPay';
        }

        if (UtilityFacades::getsettings('offlinepaymentsetting') == 'on') {
            $paymentType['offlinepayment'] = 'Pago Offline';
        }
        return view('users.payment', compact('user', 'paymentType'));
    }
    
    public function NewPayment(Request $request)
    {
        $settings = new Settings();
        
        if (UtilityFacades::getsettings('pixelpay_environment') == 'sandbox') {
            $settings->setupSandbox();
        }else {
            $settings->setupEndpoint(UtilityFacades::getsettings('pixelpay_endpoint'));
            $settings->setupCredentials(UtilityFacades::getsettings('pixelpay_key_id'), Helpers::hash('MD5', UtilityFacades::getsettings('pixelpay_secret_key')));
        }
        
        $id = \Auth::user()->id;
        $user = User::where('id', $id)->first();
        
        $card = new Card();
        $card->number = $request->card_number;
        $card->cvv2 = $request->card_cvv2;
        $card->expire_month = $request->card_expire_month;
        $card->expire_year = $request->card_expire_year;
        $card->cardholder = $request->card_holder;
        
        $billing = new Billing();
        $billing->address = $user->address;
        $billing->country = "HN";
        $billing->state = $user->state;
        $billing->city =  $user->city;
        $billing->phone = $user->phone;
        
        $item = new Item();
        $item->code = "00001";
        $item->title = "Videojuego";
        $item->price = 1;
        $item->qty = 1;
        
        $order = new Order();
        $order->id = "ORDEN-88888";
        $order->currency = "HNL";
        $order->customer_name = $user->name;
        $order->customer_email = $user->email;
        $order->addItem($item);
        
        $sale = new SaleTransaction();
        $sale->setOrder($order);
        $sale->setCard($card);
        $sale->setBilling($billing);
        
        $service = new Transaction($settings);
        
        try {
          $response = $service->doSale($sale);
        
          if (TransactionResult::validateResponse($response)) {
        
            $result = TransactionResult::fromResponse($response);
        
            $is_valid_payment = $service->verifyPaymentHash(
              $result->payment_hash,
              $order->id,
              "@s4ndb0x-abcd-1234-n1l4-p1x3l", // secret key del comercio
            );
        
            if ($is_valid_payment) {
                echo "<h1>¡Hola! El pago ha sido confirmado correctamente</h1>";
                echo "<code>" . $response->tojson() . "</code>";
            }
          } else {
            // CHECK Failed Response
            echo 'CHECK Failed Response > ';
            echo $response->message;
          }
        } catch ( Exception $e) {
          // ERROR
          echo 'Error ' + $e;
        }

    }
}