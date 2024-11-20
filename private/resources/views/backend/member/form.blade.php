@extends('backend.layouts.backend-app')
@section('title', 'Member')
@section('content')

<style>
    .avatar-option {
        height: 67px; width: 67px; border-radius: 50%; cursor: pointer; border: solid 1px lightgrey;
    }
    .avatar-option.selected {
        background: lightgreen;
    }

    .profile-img-container {
        justify-content: center;
    }

    /* .member-content-view {
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

    @media (max-width: 801px) {
        .member-content-view {
            flex-direction: column;
        }
        .uncomplete-warning {
            flex-direction: column;
        }
    } */
</style>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Member
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="box box-solid">
      <div class="box-body">
        <form class="fdata col-sm-12 p-0">
          <div class="col-sm-6">
            {{ csrf_field() }}
            <input type="hidden" name="member_id" value="{{ $member->id }}">
            <div class="row">
              <div class="col-md-12">
                <label for="">Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Nama Lengkap" value="{{ $member->name }}" name="fullname">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <label for="">Email</label>
                <input type="email" class="form-control" placeholder="Email" value="{{ $member->email }}" name="email">
              </div>
              <div class="col-md-6">
                <label for="">No Telp</label>
                <input type="text" class="form-control" placeholder="No Telp" value="{{ $member->phone }}" name="phone">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">Alamat</label>
                <textarea name="address" cols="30" rows="10" placeholder="Alamat" class="form-control">{{ $member->address }}</textarea>
              </div>
            </div>
            <br>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-md-12">
                <label for="">Gender</label>
                <div style="display: flex; flex-direction: column; gap: 12px;" class="w-100">
                  <select class="form-control select2" name="gender">
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
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">Birth Date</label>
                <input type="text" class="form-control datepicker" name="birth_date" style="cursor: pointer;" placeholder="DD-MM-YYYY" value="{{ date('d-m-Y', strtotime($member->birth_date)) }}">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">KTP</label>
                <input type="text" name="ktp" placeholder="Nomor KTP" class="form-control" value="{{$member->ktp}}" >
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">City</label>
                <?php $city = DB::table('ms_loc_city')->orderBy('province_id')->get(); ?>
                <select class="form-control select2 form-dark" name="city" style="width: 100%;">
                    <option value="">Select City</option>
                    <?php foreach ($city as $cit) : ?>
                        <?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
                        <option value="<?= $cit->city_id ?>" <?php if (old('city', $member->city_id) == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <center>
              <button class="btn btn-primary">Simpan</button>
              <button class="btn btn-back">Kembali</button>
            </center>
          </div>
        </div>
      </form>
    </div>
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

  var currentGender = "{{$member->gender}}";
  var currentTitleGender = "{{$member->title_gender}}";

  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();
    location.href = '{{ url("artmin/member") }}';
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();
    swal({
        title: "Konfirmasi",
        text: "Apakah data yang anda masukan telah sesuai?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/member/save_edit") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Berhasil', 'Data Telah Diperbaharui', 'success');
          });
        }
      });
  });

  $(document).on("click", ".avatar-option", function(e) {
      $(".avatar-option").removeClass("selected");
      $(e.target).addClass("selected");
      $("input[name='title_gender']").val($(e.target).attr("title_gender"));
      $("input[name='profile_image']").val(e.target.src);
  });

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
      if (currentGender) {
        $("select[name='gender']").val(currentGender).trigger("change.select2");
      }
      renderAvatarOptions(currentGender);
      setSelectedAvatar(currentGender, currentTitleGender);
  });
</script>


@endsection