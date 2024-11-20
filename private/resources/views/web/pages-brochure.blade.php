@extends('web.layouts.app')
@section('title', 'Brochure Download')

@section('content')



<div class="content content-dark" style="padding-top: 0px;">
    <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
        <div class="container">

            <?php
            // foreach ($parent_brochure as $key => $value) {
            //     $brochure = DB::table('ms_categories')->where('brochure', '!=', NULL)->where('parent_id', $value->category_id)->orderBy('ordering')->get();
            //     if (!empty($brochure)) {
            ?>
            <div class="row">
                <?php
                if (!empty($brochure)) {
                    foreach ($brochure as $key => $row) {
                ?>
                        <div class="col-sm-3">
                            <a class="brochure-items" href="{{ (substr($row->brochure,0,4) == 'http' ? '' : url('uploads/brochure/') . '/' ) . $row->brochure  }}" target="_blank" download="">
                                <div class="brochure-img">
                                    <img src="{{ $row->image }}">
                                </div>
                                <div class="brochure-title">
                                    {{ $row->name }}
                                </div>
                                <div style="text-align: center;">
                                    <button class="btn btn-white">Download Brochure</button>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <?php
            //     }
            // }
            ?>
        </div>
    </div>
</div>

@endsection