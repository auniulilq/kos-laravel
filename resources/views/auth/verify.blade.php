@extends('layouts.app')

@section('content')
<div class="brutalist-container" style="max-width: 600px; margin: 50px auto; padding: 20px;">
    <div class="brutalist-title">
        VERIFIKASI EMAIL
    </div>

    <div class="brutalist-card" style="border: 4px solid #000; background: #fff; padding: 20px; box-shadow: 10px 10px 0px #000;">
        
        @if (session('resent'))
            <div class="brutalist-success" style="background: #00FF00; padding: 10px; border: 3px solid #000; margin-bottom: 20px; font-weight: bold;">
                {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
            </div>
        @endif

        <div class="brutalist-text" style="font-size: 18px; font-weight: bold; margin-bottom: 20px; line-height: 1.5;">
            {{ __('Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.') }}
            <br><br>
            {{ __('Jika Anda tidak menerima email tersebut') }},
        </div>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="brutalist-button" style="background: #FFF5CC; width: auto; padding: 10px 20px; font-size: 16px; cursor: pointer;">
                {{ __('KLIK DI SINI UNTUK KIRIM ULANG') }} →
            </button>
        </form>
    </div>
</div>
@endsection