<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Cartalyst\Stripe\Stripe;
use Stripe;
use App\Http\Requests\CheckoutRequest;
use Cart;
// use Illuminate\Support\Facades\Stripe;
// use Illuminate\Support\Facades\Schema;


class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('checkout');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        // dd("hola");
        try {
            $contents=Cart::content()->map(function($item){
                return $item->model->slug.','.$item->qty;

            })->values()->toJson();
            $charge = Stripe::charges()->create([
             
                'currency' => 'USD',
                'amount'   => Cart::total()/100,
                'source'=> $request->stripeToken,
                'description'=> 'Order',
                'receipt_email'=> $request->mail,
                 'metadata'=>[
                     'contents'=>$contents,
                     'quantity'=>Cart::instance('default')->count(),

                 ],
                // 'customer' => $customer['id']
            ]);      
            Cart::instance('default')->destroy();
            return redirect()->route('confirmation.index')->with('success_message','Thank you! HAS PAGADO CORRECTAMENTE');
        } catch (CardErrorException $e) {
            return back()->withErrors('Error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
