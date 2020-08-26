@extends('layout')

@section('title', 'Checkout')

@section('extra-css')

<script src="https://js.stripe.com/v3/"></script>

@endsection

@section('content')

<div class="container">
  @if (session()->has('success_message'))
  <div class="alert-success alert">
      {{session()->get('success_message')}}
  </div>

@endif

@if(count($errors)>0)
  <div class="alert-danger alert">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
          @endforeach
      </ul>
  </div>


@endif

  <h1 class="checkout-heading stylish-heading">Checkout</h1>
  <div class="checkout-section">
    <div>
    <form action="{{route('checkout.store')}}" method="POST" id="payment-form">
       {{ csrf_field() }}
        <h2>Billing Details</h2>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" value="">
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="">
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" class="form-control" id="address" name="address" value="">
        </div>

        <div class="half-form">
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="">
          </div>
          <div class="form-group">
            <label for="province">Province</label>
            <input type="text" class="form-control" id="province" name="province" value="">
          </div>
        </div> <!-- end half-form -->

        <div class="half-form">
          <div class="form-group">
            <label for="postalcode">Postal Code</label>
            <input type="text" class="form-control" id="postalcode" name="postalcode" value="">
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="">
          </div>
        </div> <!-- end half-form -->

        <div class="spacer"></div>

        <h2>Payment Details</h2>

        <div class="form-group">
          <label for="name_on_card">Name on Card</label>
          <input type="text" class="form-control" id="name_on_card" name="name_on_card" value="">
        </div>
        <div class="form-group">
          <div class="form-row">
            <label for="card-element">
              Credit or debit card
            </label>
            <div id="card-element">
              <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
          </div>
        </div>



        {{-- <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="">
                    </div> --}}
        {{-- 
                    <div class="form-group">
                        <label for="cc-number">Credit Card Number</label>
                        <input type="text" class="form-control" id="cc-number" name="cc-number" value="">
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="expiry">Expiry</label>
                            <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/DD">
                        </div>
                        <div class="form-group">
                            <label for="cvc">CVC Code</label>
                            <input type="text" class="form-control" id="cvc" name="cvc" value="">
                        </div>
                    </div> <!-- end half-form --> --}}

        <div class="spacer"></div>

        <button type="submit" class="button-primary full-width" id="complete-order">Complete Order</button>


      </form>
    </div>



    <div class="checkout-table-container">
      <h2>Your Order</h2>
      @foreach (Cart::content() as $item)

      <div class="checkout-table">
        <div class="checkout-table-row">
          <div class="checkout-table-row-left">
          <img src="{{asset('img/products/'.$item->model->slug.'.jpg')}}" alt="item" class="checkout-table-img">
            <div class="checkout-item-details">
              <div class="checkout-table-item">{{$item->model->name}}</div>
              <div class="checkout-table-description">{{$item->model->detail}}</div>
              <div class="checkout-table-price">{{$item->model->presentPrice()}}</div>
            </div>
          </div> <!-- end checkout-table -->

          <div class="checkout-table-row-right">
            <div class="checkout-table-quantity">1</div>
          </div>
        </div> <!-- end checkout-table-row -->

        @endforeach


     


        <div class="checkout-totals">
          <div class="checkout-totals-left">
            Subtotal <br>
            @if (session()->has('coupon'))

            Discount ({{session()->get('coupon')['name']}})
          <form action="{{route('coupon.destroy')}}" method="post" style="display: inline;">
            {{ csrf_field() }}
            {{method_field('delete')}}
            <button type="submit" style="font-size: 14px">Remove</button>
          </form>
          <br>  
          {{-- <hr> --}}
          {{-- New Subtotal <br> --}}

          @endif

          Tax <br>
            <span class="checkout-totals-total" >Total</span>

          </div>

          <div class="checkout-totals-right">
            {{presentPrice(Cart::Subtotal())}} <br>
            {{-- -$750.00 <br> --}}
          {{-- nuevas variables--}}
            @if (session()->has('coupon'))
            {{-- -{{presentPrice($newSubtotal)}} <br> --}}
            
            -{{presentPrice($discount)}} <br>
            {{-- <hr> --}}

            @endif
            {{presentPrice($newTax)}}<br>
            <span class="checkout-totals-total">{{presentPrice($newTotal)}}</span>
          </div>
        </div> <!-- end checkout-totals --> 
      </div>
         {{-- cupones --}}
         @if (!session()->has('coupon'))

         <div>
          <a href="#" class="have-code">Have a Code?</a>

          <div class="have-code-container">
          <form action="{{route('coupon.store')}}" method="POST">
                  {{ csrf_field() }}
                  <input type="text" name="coupon_code" id="coupon_code">
                  <button type="submit" class="button button-plain">Apply</button>
              </form> 
          </div> <!-- end have-code-container -->
          
         </div>
         @endif
    
    </div> <!-- end checkout-section -->
  </div>

  @endsection
  @section('extra-js')
  <script>
    // Create a Stripe client.
var stripe = Stripe('pk_test_51HDFYHBMCV4qhDcoNFRluedCVOmPnGra6B5PLBIJPcAbL54n1kfcth8vTAMyuMZ3ZkeMbO6P8QfnBzy41dZLchsF00llIO54Xn');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Roboto","Helvetica Neue", H   elvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style, hidePostalCode:true});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.on('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();
  document.getElementById('complete-order').disabled=true;

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
      document.getElementById('complete-order').disabled=true;

    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);

    }
  });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}


  </script>

  @endsection