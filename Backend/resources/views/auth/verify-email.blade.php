<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Vui lòng xác minh địa chỉ email của bạn bằng cách nhấn vào liên kết chúng tôi đã gửi qua email.
    </div>

    @if (session('message'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('message') }}
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-info">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="mt-4 flex items-center justify-between">
            <button type="submit" class="btn btn-primary">
                Gửi lại email xác minh
            </button>
        </div>
    </form>
</x-guest-layout>


