@extends('web.layouts.app')
@section('title', 'Service Request')

@section('content')



<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-3">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">Service Request</h1>
                <div class="member-content">
                    <div class="warranty-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class=" warranty-item-img">
                                    <img src="{{ $product->product_image??'' }}">
                                </div>
                            </div>
                            <div class="col-sm-7" style="padding-top: 20px;">
                                <div class="warranty-prod-summary">
                                    <div class="warranty-prod-item warranty-prod-name">
                                        {{ $product->product_name??'' }}
                                    </div>
                                    <div class="warranty-prod-item">
                                        <strong>Warranty No:</strong> {{ $warranty->warranty_no }}
                                    </div>
                                    <div class="warranty-prod-item">
                                        <strong>Serial No:</strong> {{ $warranty->serial_no }}
                                    </div>
                                    <!-- <div class="warranty-prod-item">
                                        <strong>Active Until:</strong> 05-06-2023
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-request-form">
                        <form action="" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="warranty_id" value="{{ $warranty->warranty_id }}">
                            <div class="form-group">
                                <label>Kategori Masalah</label>
                                <select class="form-control select2" name="problem_category">
                                    <option value="">Pilih kendala</option>
                                    <option value="Tidak Nyala">Tidak nyala</option>
                                    <option value="Tidak Dingin">Tidak dingin</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                                @if ($errors->has('problem_category'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('problem_category') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Masalah</label>
                                <textarea class="form-control form-dark" name="problem" rows="10" placeholder="Mohon deskripsikan masalah pada produk anda.">{{ old('problem')}}</textarea>
                                @if ($errors->has('problem'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('problem') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Preferensi Tanggal Kunjungan</label>
                                <input type="text" class="form-control form-dark" id="prefered_date" name="prefered_date" value="{{ old('prefered_date', date('d-m-Y', strtotime('+1 day'))) }}" placeholder="dd-mm-yyyy">
                                @if ($errors->has('prefered_date'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('prefered_date') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Preferensi Jam Kunjungan</label>
                                <select class="form-control form-dark" name="prefered_time">
                                    <?php foreach ($service_time as $time): ?>
                                        <option value="<?= $time->id ?>"><?= $time->time ?></option>
                                    <?php endforeach; ?>
                                </select>
                                @if ($errors->has('prefered_time'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('prefered_time') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control form-dark" name="name" value="{{ old('name', $warranty->reg_name) }}" placeholder="Nama">
                                @if ($errors->has('name'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('name') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="text" class="form-control form-dark" name="phone" value="{{ old('phone', $warranty->reg_phone) }}" placeholder="Phone Number">
                                @if ($errors->has('phone'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('phone') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Alamat Kunjungan</label>
                                <textarea class="form-control form-dark" name="address" rows="5" placeholder="Alamat kunjungan">{{ old('address', $warranty->reg_address )}}</textarea>
                                @if ($errors->has('address'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('address') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <?php
                                $city = DB::table('ms_loc_city')->orderBy('province_id')->get();
                                ?>
                                <label>Kota</label>
                                <select class="form-control select2" name="city">
                                    <option value="">Pilih Kota</option>
                                    <?php foreach ($city as $cit): ?>
                                        <?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
                                        <option value="<?= $cit->city_id ?>" <?php if(old('city', $warranty->reg_city_id) == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                @if ($errors->has('city'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('city') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-white">Request Support</button>
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
