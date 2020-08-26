<?php

Route::get('/', 'LandingPageController@index')->name('landing-page');

Route::get('/shop', 'ShopController@index')->name('shop.index');
Route::get('/shop/{product}', 'ShopController@show')->name('shop.show');
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart', 'CartController@store')->name('cart.store');
Route::delete('/cart/{product}','CartController@destroy')->name('cart.destroy');
Route::patch('/cart/{product}','CartController@update')->name('cart.update');

Route::post('/cart/switchToSaveForLater/{product}','CartController@switchToSaveForLater')->name('cart.switchSaveForLater');
Route::delete('/saveForLater/{product}','SaveForLaterController@destroy')->name('saveForLater.destroy');
Route::post('/saveForLater/switchToCart/{product}','SaveForLaterController@switchToCart')->name('saveForLater.switchToCart');

/*Checkout*/
Route::get('/checkout','CheckoutController@index')->name('checkout.index');
Route::post('/checkout','CheckoutController@store')->name('checkout.store');
Route::get('/thankyou','ConfirmController@index')->name('confirmation.index');

Route::post('/coupon','CouponsController@store')->name('coupon.store');

Route::delete('/coupon','CouponsController@destroy')->name('coupon.destroy');


Route::get('empty',function(){
    // foreach(Cart::content() as $item){

    //     dd($item->model);
    // }
    Cart::instance('saveForLater')->destroy();

});
