<div class="row">
    @foreach ($products as $product)
        <div class="col-sm-6 col-xl-4">
            <div class="productItem01">
                <div class="pi01Thumb ratio ratio-1x1 position-relative overflow-hidden">
                    <img class="main-img img-fluid w-100 h-100 object-fit-cover position-absolute top-0 start-0"
                        src="{{ asset($product->product_image ?? 'images/no-image.jpg') }}" alt="{{ $product->name }}">
                </div>
                <div class="pi01Details">
                    <h3 class="product-name h5 mb-2">
                        <a href="{{ route('client.shop.show', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    <div class="price fw-bold text-danger mb-2">
                        {{ number_format($product->price ?? 0) }} VNĐ
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
