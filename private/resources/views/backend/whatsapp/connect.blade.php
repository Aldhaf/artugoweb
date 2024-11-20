<div class="card">
    <div class="card-body" style="text-align: center; padding-top: 44px;">
        @if($session && $session->status == 'waiting' && $session->qr_code)
        {!! QrCode::size(400)->generate($session->qr_code) !!}
        @elseif($session && $session->status == 'used')
        <strong><h1>✅ Client Whatsapp sudah tersambung.</h1></strong>
        @elseif(!$session)
        <strong><h1>Tidak ada Client Whatsapp session!</h1></strong>
        @endif
    </div>
</div>