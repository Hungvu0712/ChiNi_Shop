<div class="row g-4">
            @foreach ($products as $product)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="product-card card h-100 border-0">
                    <div class="product-img-container position-relative">
                        <img class="product-img main-img"
                             src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                             alt="{{ $product->name }}">
                        <img class="product-img hover-img position-absolute top-0 start-0"
                             src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}"
                             alt="{{ $product->name }}">
                    </div>

                    <div class="card-body">
                        <h5 class="card-title mb-2">
                            <a href="{{ route('client.shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h5>

                        <div class="mb-3">
                            @if (!empty($product->colorData))
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach ($product->colorData as $color)
                                <span class="color-option"
                                      style="background-color: {{ $color['hex'] }};"
                                      data-image="{{ asset($color['image']) }}"
                                      data-name="{{ $color['variant_name'] }}"
                                      data-price="{{ number_format($color['price']) }} VNĐ"
                                      title="{{ ucfirst($color['name']) }}"
                                      data-bs-toggle="tooltip"></span>
                                @endforeach
                            </div>
                            @endif

                            @foreach ($product->attributesGroup as $name => $values)
                                @if ($name != 'Màu sắc')
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach ($values as $value)
                                    <span class="attribute-badge bg-light text-dark border">{{ $value }}</span>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger fw-bold">{{ number_format($product->price ?? 0) }} VNĐ</span>
                            <a href="{{ route('client.shop.show', $product->slug) }}" class="btn btn-sm btn-outline-dark">
                                <i class="fas fa-eye me-1"></i> Xem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
