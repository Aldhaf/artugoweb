@extends('web.layouts.app')
@section('title', 'Installation Request')

@section('content')



<div class="content content-dark" style="padding-top: 0px;">
    <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2">
                    <div class="warranty-register-success" style="text-align: left;">
                        <h1><b>Request Instalasi</b></h1>
                        Silahkan menentukan waktu preferensi anda. <br>
                        <div class="warranty-item" style="font-size: 16px; margin: 20px 0px;">
                            <div class="row">
                                <div class="col-sm-5">
                                    <a href="{{ url('member/warranty/' . $warranty->warranty_no) }}" target="_blank" style="padding: 20px;">
                                        <img src="{{ $product->product_image??'' }}">
                                    </a>
                                </div>
                                <div class="col-sm-7">
                                    <div class="warranty-prod-summary" style="padding-top: 50px;">
                                        <a href="{{ url('member/warranty/' . $warranty->warranty_no) }}" target="_blank">
                                            <div class="warranty-prod-item warranty-prod-name">
                                                {{ $product->product_name??'' }}
                                            </div>
                                            <div class="warranty-prod-item">
                                                <strong>Warranty No:</strong> {{ $warranty->warranty_no }}
                                            </div>
                                            <div class="warranty-prod-item">
                                                <strong>Serial No:</strong> {{ $warranty->serial_no }}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="" method="post" style="font-size: 16px;">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Preferensi Tanggal Kunjungan</label>
                                <input type="text" class="form-control form-dark" id="prefered_date" name="prefered_date" value="{{ old('prefered_date', date('d-m-Y', strtotime('+1 day'))) }}" placeholder="dd-mm-yyyy">
                            </div>
                            <div class="form-group">
                                <label>Preferensi Jam Kunjungan</label>
                                <select class="form-control form-dark" name="prefered_time">
                                    <?php foreach ($service_time as $time): ?>
                                        <option value="<?= $time->id ?>"><?= $time->time ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control form-dark" name="name" value="{{ old('name', $warranty->reg_name) }}" placeholder="Nama">
                            </div>
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="text" class="form-control form-dark" name="phone" value="{{ old('phone', $warranty->reg_phone) }}" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                <label>Alamat Kunjungan</label>
                                <textarea class="form-control form-dark" name="address" rows="5" placeholder="Alamat kunjungan">{{ old('address', $warranty->reg_address )}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Kota</label>
                                <?php
                                $city = DB::table('ms_loc_city')->orderBy('province_id')->get();
                                ?>
                                <select class="form-control select2" name="city">
                                    <option value="">Pilih Kota</option>
                                    <?php foreach ($city as $cit): ?>
                                        <option value="<?= $cit->city_id ?>" <?php if(old('city', $warranty->reg_city_id) == $cit->city_id) echo "selected"; ?>><?= $cit->city_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-white">Request Instalasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    var date = new Date();
    date.setDate(date.getDate()+1);

    $('#prefered_date').datepicker({
        daysOfWeekDisabled: [0],
        startDate: date,
        format: 'dd-mm-yyyy',
    })
</script>
@endpush

@endsection
