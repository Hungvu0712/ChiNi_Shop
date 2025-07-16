<!-- BEGIN: Header 01 Section -->
<header class="header01 isSticky">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="headerInner01">
                    <div class="logo">
                        <a href="/">
                            <img src="{{ asset('client/images/logoshop.png') }}" alt="ChiniShop" />
                        </a>
                    </div>
                    <div class="mainMenu">
                        <ul>
                            @foreach ($menus as $menu)
                                <li class="{{ $menu->children->count() > 0 ? 'dropdown' : '' }}">
                                    <a href="{{ $menu->url }}">
                                        <span>{{ $menu->name }}</span>
                                        @if ($menu->children->count() > 0)
                                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                                        @endif
                                    </a>

                                    @if ($menu->children->count() > 0)
                                        <ul class="dropdown-menu">
                                            @foreach ($menu->children as $child)
                                                <li class="{{ $child->children->count() > 0 ? 'dropdown' : '' }}">
                                                    <a href="{{ $child->url }}">
                                                        <span>{{ $child->name }}</span>
                                                        @if ($child->children->count() > 0)
                                                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                                                        @endif
                                                    </a>

                                                    @if ($child->children->count() > 0)
                                                        <ul class="dropdown-menu">
                                                            @foreach ($child->children as $grandchild)
                                                                <li>
                                                                    <a href="{{ $grandchild->url }}">
                                                                        {{ $grandchild->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="accessNav">
                        <a href="javascript:void(0);" class="menuToggler"><i class="fa-solid fa-bars"></i>
                            <span>Menu</span></a>
                        <div class="anSocial">
                            <div class="ansWrap">
                                <a class="fac" href="https://www.youtube.com/"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                                <a class="twi" href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a>
                                <a class="lin" href="javascript:void(0);"><i
                                        class="fa-brands fa-linkedin-in"></i></a>
                                <a class="ins" href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                            <a class="tog" href="javascript:void(0);"><i class="fa-solid fa-share-alt"></i></a>
                        </div>
                        <div class="anSelects">
                            <div class="anSelect">
                                <select name="languages">
                                    <option value="ENG">EN</option>
                                    <option value="ARA">AR</option>
                                    <option value="GER">GR</option>
                                    <option value="SPA">SP</option>
                                </select>
                            </div>
                        </div>
                        <div class="anItems">
                            <div class="anSearch"><a href="javascript:void(0);"><i class="fa-solid fa-search"></i></a>
                            </div>
                            <div class="anCart">
                                <a href="{{ route('cart.index')}}"><i
                                        class="fa-solid fa-shopping-cart"></i><span>3</span></a>
                                <div class="cartWidgetArea">
                                    <div class="cartWidgetProduct">
                                        <img src="{{ asset('client/images/cart/1.jpg') }}" alt="Marine Design">
                                        <a href="shop_details1.html">Ulina luxurious bag for men women</a>
                                        <div class="cartProductPrice clearfix">
                                            <span class="price"><span><span>$</span>19.00</span></span>
                                        </div>
                                        <a href="javascript:void(0);" class="cartRemoveProducts"><i
                                                class="fa-solid fa-xmark"></i></a>
                                    </div>
                                    <div class="totalPrice">Subtotal: <span
                                            class="price"><span><span>$</span>112.00</span></span></div>
                                    <div class="cartWidgetBTN clearfix">
                                        <a class="cart" href="cart.html">View Cart</a>
                                        <a class="checkout" href="checkout.html">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="anSupport">
                            <div class="" style="margin-top: 7px">
                                @auth
                                    <div class="dropdown">
                                        <p class="dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownUser">
                                            @role('admin')
                                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Trang quản
                                                        trị</a></li>
                                            @endrole

                                            @role('staff')
                                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Cộng tác
                                                        viên</a>
                                                </li>
                                            @endrole


                                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Thông tin cá
                                                    nhân</a></li>
                                            <li><a class="dropdown-item" href="{{ route('address') }}">Địa chỉ
                                                </a></li>

                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <x-responsive-nav-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                    Đăng xuất
                                                </x-responsive-nav-link>
                                            </form>
                                        </ul>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}">Đăng nhập |</a>
                                    <a href="{{ route('register') }}">Đăng ký</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</header>
<!-- END: Header 01 Section -->
