@extends('web.layouts.app')
@section('title', 'Service')

@section('content')


<style media="screen">
    #warranty-service-section {
        display: none;
    }
</style>



<div class="features-page pt-features">
    <div class="features-card pt-features">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="section-tittle mb-60 text-center wow fadeInUp" data-wow-duration="2s"
                        data-wow-delay=".2s">
                        <h2>
                            <?php
                            if (!isset($category->title)) {
                                echo "Features";
                            }
                            else{
                                echo $category->title." Features";
                            }
                            ?>
                        </h2>
                        <hr class="hr-1 mb-card">
                    </div>
                </div>
                <?php if (count($features) == 0): ?>
                <div class="col-sm-12">
                    <h5>Sorry, there's no features data</h5>
                </div>
                <?php endif; ?>
                <?php foreach ($features as $row): ?>
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="single-features-card mb-card">
                        <div class="location-img ">
                            <img src="{{ $row->image }}" alt=" ">
                        </div>
                        <h1 style="@if($row->id==7 || $row->id == 12 || $row->id == 19 || $row->id == 20) color: black @endif">
                            {{ $row->title }}</h1>
                        <div class="location-details ">
                            <p style="@if($row->id==7 || $row->id == 12 || $row->id == 19 || $row->id == 20) color: black @endif">
                                <?php
                                if($row->meta_desc != ''){
                                    echo substr($row->meta_desc, 0, 100) . '...';
                                }
                                else{
                                    echo substr(strip_tags($row->content), 0, 100) . '...';
                                }
                                ?>
                            </p>
                            <a href="{{ url('article/read/'.$row->slug) }}" class="btn-purple">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

@endsection