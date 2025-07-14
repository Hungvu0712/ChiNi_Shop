@extends('client.layouts.master')
@section('title', 'Cart')
@section('content')
    <!-- BEGIN: Search Popup Section -->
        <section class="popup_search_sec">
            <div class="popup_search_overlay"></div>
            <div class="pop_search_background">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="popup_logo">
                                <a href="index.html"><img src="images/logo2.png" alt="Ulina"></a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <a href="javascript:void(0);" id="search_Closer" class="search_Closer"></a>
                        </div>
                    </div>
                </div>
                <div class="middle_search">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="popup_search_form">
                                    <form method="get" action="#">
                                        <input type="search" name="s" id="s" placeholder="Type Words and Hit Enter">
                                        <button type="submit"><i class="fa-solid fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Search Popup Section -->

        <!-- BEGIN: Page Banner Section -->
        <section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Shopping Cart</h2>
                            <div class="pageBannerPath">
                                <a href="index.html">Home</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Cart</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Page Banner Section -->

        <!-- END: Cart Page Section -->
        <section class="cartPageSection woocommerce">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cartHeader">
                            <h3>Your Cart Items</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <table class="shop_table cart_table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Item Name</th>
                                    <th class="product-name">&nbsp;</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-remove">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="product-thumbnail">
                                        <a href="shop_details1.html"><img src="images/cart/1.jpg" alt="Cart Item"/></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details1.html">Ulina luxurious bag for men women</a>
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>$48.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <div class="pi01Price">
                                            <ins>$48.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="javascript:void(0);" class="remove"><span></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="product-thumbnail">
                                        <a href="shop_details2.html"><img src="images/cart/2.jpg" alt="Cart Item"/></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details2.html">Nasio stainless steel watch</a>
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>$52.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <div class="pi01Price">
                                            <ins>$52.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="javascript:void(0);" class="remove"><span></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="product-thumbnail">
                                        <a href="shop_details1.html"><img src="images/cart/3.jpg" alt="Cart Item"/></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details1.html">Winner men’s comfortable t-shirt</a>
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>$33.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <div class="pi01Price">
                                            <ins>$33.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="javascript:void(0);" class="remove"><span></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="product-thumbnail">
                                        <a href="shop_details2.html"><img src="images/cart/4.jpg" alt="Cart Item"/></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details2.html">Ulina easy carry bag for women</a>
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>$99.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                            <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                            <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <div class="pi01Price">
                                            <ins>$99.00</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="javascript:void(0);" class="remove"><span></span></a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="actions">
                                    <td colspan="2" class="text-start">
                                        <a href="shop_full_width.html" class="ulinaBTN"><span>Continue Shopping</span></a>  
                                    </td>
                                    <td colspan="4" class="text-end">
                                        <a href="shop_full_width.html" class="ulinaBTN2">Update Cart</a>  
                                        <a href="shop_full_width.html" class="ulinaBTN2">Clear All</a>  
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row cartAccessRow">
                    <div class="col-8"><h3>Số tiền tạm tính</h3></div>
                    <div class="col-lg-4">
                        <div class="col-sm-12 cart_totals">
                            <table class="shop_table shop_table_responsive">
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins>$133.00</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="cart-shipping">
                                    <th>Shipping</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins>$10.00</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Grand Total</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins>$143.00</ins>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <a href="checkout.html" class="checkout-button ulinaBTN">
                                <span>Proceed to checkout</span>
                            </a>
                            <p class="cartHints">Checkout with multiple address</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Cart Page Section -->
@endsection