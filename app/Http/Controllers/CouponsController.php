<?php

namespace App\Http\Controllers;
use App\Coupon;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Redirect;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $coupon=Coupon::where('code',$request->coupon_code)->first();
        if(!$coupon){
            return redirect()->route('checkout.index')->withErrors('Codigo invalido. Por favor intente otra vez.');
        }
        session()->put('coupon',[
            'name' => $coupon->code,
            'discount' => $coupon->discount(Cart::total()),
        ]);
        //
        return redirect()->route('checkout.index')->with('success_message','¡El cupon ha sido aplicado!');
    }

    public function destroy()
    {
        session()->forget('coupon');
        return redirect()->route('checkout.index')->with('success_message','¡El cupon ha sido eliminado!');
    }
}
