@extends('web.layouts.app')
@section('title', 'Member Profile')

@section('content')

<style>
    .avatar-option {
        height: 75px; width: 75px; border-radius: 50%; cursor: pointer; border: solid 1px white;
    }
    .avatar-option.selected {
        background: lightgreen;
    }
    .member-content-view {
        display: flex;
        flex-direction: row;
        gap: 24px;
    }
    .uncomplete-warning {
        gap: 4px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .profile-img-container {
        justify-content: center;
    }

    @media (max-width: 801px) {
        .member-content-view {
            flex-direction: column;
        }
        .uncomplete-warning {
            flex-direction: column;
        }
    }
</style>

<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <!-- <div class="col-sm-3">
                <div class="member-sidebar">
                    <div class="sidebar-user"><b>Hi, {{ Session::get('member_name') }}</b></div>
                    <a href="{{ url('member/dashboard') }}" class="sidebar-item">Dashboard</a>
                    <a href="{{ url('member/service') }}" class="sidebar-item">Service</a>
                    <a href="{{ url('member/profile') }}" class="sidebar-item active">Profile</a>
                    <a href="{{ url('member/logout') }}" class="sidebar-item">Logout</a>
                </div>
            </div> -->
            <div class="col-sm-3">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">Profile</h1>
                <div class="member-content">
                    @if(!$complete_profile)
                    <div class="uncomplete-warning prod-info font-weight-bold warranty-prod-item bg-warning text-body rounded-3 py-2 px-4 pointer mb-4">
                        <span>Complete your profile to earn points!</span>
                        <a id="btn-open-edit-profile" href="javascript:void(0)" class="btn btn-xs btn-success btn-xs m-0 bg-success" style="width: 160px; color: white; border: none;">Edit Profile &nbsp;&nbsp;<i class="fa fa-edit"></i></a>
                    </div>
                    @endif
                    <div class="member-content-view">
                        <div class="d-flex profile-img-container">
                            <img src="{{$member->profile_image}}" style="height: 120px; width: 120px; min-width: 120px; border-radius: 50%; background-color: white;"/>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1;">
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Name</span>
                                <span id="name">{{$member->name}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Gender</span>
                                <span id="gender" data="{{$member->gender}}">
                                    <?php
                                        if($member->gender == "male") {
                                            print('Male');
                                        } elseif($member->gender == "female") {
                                            print('Female');
                                        }
                                    ?>
                                </span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Birth Date</span>
                                <span id="birth_date">{{date_format(new DateTimeImmutable($member->birth_date), 'd-m-Y')}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Phone / Whatsapp</span>
                                <span id="phone">{{$member->phone}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">KTP</span>
                                <span id="ktp">{{$member->ktp}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Email</span>
                                <span id="email">{{$member->email}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Address</span>
                                <span id="address">{{$member->address}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">City</span>
                                <span id="city" data="{{$member->city_id}}">{{$member->city}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Testimony</span>
                                <span id="testimony">{{$member->testimony}}</span>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; border-bottom: 1px solid gray; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Balance Points</span>
                                <div class="prod-info font-weight-bold warranty-prod-item bg-success text-white rounded-3 px-2 pointer">{{ number_format($total_points,2) }}</div>
                            </div>
                            @if($complete_profile)
                            <div style="display: flex; justify-content: end;" class="w-100 mt-4">
                                <a id="btn-open-edit-profile" href="javascript:void(0)" class="btn btn-secondary btn-xs m-0" style="width: 160px; color: white; border-color: white;">Edit Profile &nbsp;&nbsp;<i class="fa fa-edit"></i></a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <form class="member-content-edit hidden" action="{{url('member/profile')}}" autocomplete="off" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: row; gap: 24px;">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$member->id}}">
                        <div style="display: flex; flex-direction: column; gap: 6px; flex: 1;">
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Name</span>
                                <input type="text" name="name" placeholder="Name" class="form-control form-dark">
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Gender</span>
                                <div style="display: flex; flex-direction: column; gap: 12px;" class="w-100">
                                    <select class="form-control form-dark select2" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <div style="display: flex; gap: 6px;">
                                        <img class="avatar-option male kaka" title_gender="kaka" src="{{ url('') }}/assets/avatars/young-male-1.png" />
                                        <img class="avatar-option female kaka" title_gender="kaka" src="{{ url('') }}/assets/avatars/young-female-1.png" />
                                        <img class="avatar-option male bapak" title_gender="bapak" src="{{ url('') }}/assets/avatars/adult-male-1.png" />
                                        <img class="avatar-option female ibu" title_gender="ibu" src="{{ url('') }}/assets/avatars/adult-female-1.png" />
                                    </div>
                                    <input type="hidden" name="title_gender" value="{{$member->title_gender}}">
                                    <input type="hidden" name="profile_image" value="{{$member->profile_image}}">
                                </div>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Birth Date</span>
                                <input type="text" class="form-control form-dark datepicker-today" name="birth_date" style="cursor: pointer;" placeholder="DD-MM-YYYY">
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Phone / Whatsapp</span>
                                <input type="text" name="phone" placeholder="Phone / Whatsapp Number" class="form-control form-dark">
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">KTP</span>
                                <input type="text" name="ktp" placeholder="Nomor KTP" class="form-control form-dark">
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Email</span>
                                <input type="text" name="email" placeholder="Email Address" class="form-control form-dark">
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Address</span>
                                <textarea class="form-control form-dark" name="address" rows="4" placeholder="Address"></textarea>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">City</span>
                                <?php $city = DB::table('ms_loc_city')->orderBy('province_id')->get(); ?>
                                <select class="form-control select2 form-dark" name="city" style="width: 100%;">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cit) : ?>
                                        <?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
                                        <option value="<?= $cit->city_id ?>" <?php if (old('city', Session::get('member_city_id')) == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 6px; padding: 4px;">
                                <span style="width: 160px; min-width: 160px;">Review of Product</span>
                                <textarea name="testimony" class="w-100">{{$member->testimony}}</textarea>
                            </div>
                            <div class="w-100 d-flex flex-column align-items-center">
                                <b>Give a Rating</b>
                                <input type="hidden" name="star" value="{{ ($member->star ?? '0') }}">
                                <div>
                                    @if(empty($member->star))
                                    <i style="color:gray; cursor:pointer" data-star="1" class="rating-star rating-star-1 fa fa-star"></i>
                                    <i style="color:gray; cursor:pointer" data-star="2" class="rating-star rating-star-2 fa fa-star"></i>
                                    <i style="color:gray; cursor:pointer" data-star="3" class="rating-star rating-star-3 fa fa-star"></i>
                                    <i style="color:gray; cursor:pointer" data-star="4" class="rating-star rating-star-4 fa fa-star"></i>
                                    <i style="color:gray; cursor:pointer" data-star="5" class="rating-star rating-star-5 fa fa-star"></i>
                                    @else
                                    @for($i=1;$i <= 5; $i++ )
                                    <i style="color:{{ ($i <= $member->star ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                                    @endfor
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; justify-content: end; gap: 6px;" class="w-100 mt-4">
                                <a id="btn-cancel-profile" href="javascript:void(0)" class="btn btn-primary btn-xs m-0" style="width: 160px; color: white; border-color: white;">Cancel &nbsp;&nbsp;<i class="fa fa-undo"></i></a>
                                <a id="btn-save-profile" href="javascript:void(0)" class="btn btn-secondary btn-xs m-0" style="width: 160px; color: white; border-color: white;">Save &nbsp;&nbsp;<i class="fa fa-save"></i></a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal malert" style="color:#000; z-index: 9999999999;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; gap: 6px; align-items: center;">
                    <i class="fa fa-exclamation-circle text-danger"></i><h5 class="modal-title">Attention!</h5>
                </div>
                <button type="button" class="close close-malert" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong id="error_message" class="control-label text-danger"></strong>
            </div>
        </div>
    </div>
</div>

@push("js")
<script>

    var currentGender = "{{$member->gender}}";
    var currentTitleGender = "{{$member->title_gender}}";

    $(document).on("click", "#btn-open-edit-profile", function(e) {
        e.preventDefault();

        const name = $("span[id='name']").html();
        const gender = $("span[id='gender']").attr("data");
        const birth_date = $("span[id='birth_date']").html();
        const phone = $("span[id='phone']").html();
        const ktp = $("span[id='ktp']").html();
        const email = $("span[id='email']").html();
        const address = $("span[id='address']").html();
        const city = $("span[id='city']").attr("data");

        $("input[name='name']").val(name);
        $("select[name='gender']").val(gender).trigger("change.select2");
        $("input[name='birth_date']").val(birth_date);
        $("input[name='phone']").val(phone);
        $("input[name='ktp']").val(ktp);
        $("input[name='email']").val(email);
        $("textarea[name='address']").val(address);
        $("select[name='city']").val(city).trigger("change.select2");

        $(".member-content-edit").removeClass("hidden");
        $(".member-content-view").addClass("hidden");

    });

    $(document).on("click", "#btn-cancel-profile", function(e) {
        $(".member-content-edit").addClass("hidden");
        $(".member-content-view").removeClass("hidden");
        setSelectedAvatar(currentGender, currentTitleGender);
    });

    $(document).on("click", ".avatar-option", function(e) {
        $(".avatar-option").removeClass("selected");
        $(e.target).addClass("selected");
        $("input[name='title_gender']").val($(e.target).attr("title_gender"));
        $("input[name='profile_image']").val(e.target.src);
    });

    $(document).on("click", ".close-malert", function(e) {
        hideAlert();
    });

    $(document).on("click", "#btn-save-profile", function(e) {

        const profile = {
            name: $("input[name='name']").val(),
            gender: $("select[name='gender']").val(),
            birth_date: $("input[name='birth_date']").val(),
            phone: $("input[name='phone']").val(),
            email: $("input[name='email']").val(),
            address: $("textarea[name='address']").val(),
            city: $("select[name='city']").val(),
        };

        const valid = validateEditProfile(profile);
        if (!valid) {
            return;
        }

        $(".member-content-edit").submit();

    });

    function validateEditProfile(data) {
        if (!data.gender) {
            showAlert("Please select gender!");
            return false;
        }
        if (!phone) {
            showAlert("Please input your phone number!");
            return false;
        }

        return true;
    }

    function showAlert(message="") {
        $("#error_message").html(message);
        $(".malert").modal("show");
    }

    function hideAlert() {
        $("#error_message").html("");
        $(".malert").modal("hide");
    }

    function renderAvatarOptions(gender="") {
        if(gender==="male") {
            $(".avatar-option.male").removeClass("hidden");
            $(".avatar-option.female").addClass("hidden");
        } else if(gender==="female") {
            $(".avatar-option.male").addClass("hidden");
            $(".avatar-option.female").removeClass("hidden");
        }
    }

    function setSelectedAvatar(gender, title_gender) {
        $(".avatar-option.selected").removeClass("selected");
        if (gender, title_gender) {
            $(`.avatar-option.${gender}.${title_gender}`).addClass("selected");
        }
    }

    $(document).ready(function () {
        $("select[name='gender']").on("change", function(e) {
            renderAvatarOptions(e.target.value);
        });
        renderAvatarOptions(currentGender);
        setSelectedAvatar(currentGender, currentTitleGender);
    });

</script>
@endpush

@endsection
