@extends('backend.layouts.backend-app')
@section('title', Request::segment(2) == 'article' ? 'Article' : 'Product Knowledge')
@section('content')

<div class="content">
    <div class="container">
        <div class="row justify-content-md-center my-4">
            <div class="mb-4 col-md-6 rounded text-center">
                <input type="search" class="form-control" name="keywords" placeholder="Search..." value="">
            </div>
        </div>
        <div class="row data-contaier">
            <?php if (count($article) == 0): ?>
                <div class="col-md-12">
                    <h5>Sorry, there's no article data</h5>
                </div>
            <?php endif; ?>
            <?php foreach ($article as $row): ?>
                <div class="col-6 col-md-4">
                    <a href="{{ url('artmin/product-knowledge/read/'.$row->slug) }}" class="article-cards">
                        <div class="article-img" style="background: url('{{ $row->image }}') no-repeat center center; background-size: cover;">
                        </div>
                        <div class="article-meta">
                            <h2>{{ $row->title }}</h2>
                            <div class="article-excerpt">
                                <?php
                                if($row->meta_desc != ''){
                                    echo substr($row->meta_desc, 0, 100) . '...';
                                }
                                else{
                                    echo substr(strip_tags($row->content), 0, 100) . '...';
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="article-pagination">
                    {{ $article->render() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var keyword = "";

    function loadList() {

        if (keyword === $("input[name='keywords']").val()) {
            return;
        }

        keyword = $("input[name='keywords']").val();
        location.search = `?keywords=${keyword}`;

        // $.ajax({
        //     type: "GET",
        //     url: `{{ url("/artmin/" . Request::segment(2) . "?keywords=") }}${keyword}`,
        //     data: { "_token": "{{ csrf_token() }}" },
        //     timeout: 600000,
        //     success: function (data) {
        //         // swal(data.success ? 'Berhasil' : 'Gagal', `Attachment ${data.success ? 'berhasil' : 'gagal'} dihapus`, data.success ? 'success' : 'error');
        //         console.log("SUCCESS", data.data);
        //         $(".data-contaier").empty();
        //         for (const post of data.data) {
        //             $(".data-contaier").append(`<div class="col-6 col-md-4">
        //                 <a href="http://local.artugo.co.id/artmin/product-knowledge/read/${post.slug}" class="article-cards">
        //                     <div class="article-img" style="background: url('${post.image}') no-repeat center center; background-size: cover;">
        //                     </div>
        //                     <div class="article-meta">
        //                         <h2>Xxxx Yyyy Zzzzzz</h2>
        //                         <div class="article-excerpt">
        //                             ${post.meta_desc}...
        //                         </div>
        //                     </div>
        //                 </a>
        //             </div>`
        //             );
        //         }
        //     },
        //     error: function (e) {
        //         // swal('Gagal', 'Attachment gagal dihapus', 'error');
        //         console.log("ERROR", e);
        //     }
        // });
    }

    function debounce(func, timeout = 500) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        $("input[name='keywords']").val(urlParams.get('keywords'));
        $("input[name='keywords']").keyup(debounce(() => loadList()));
        $("input[name='keywords']").focus();
    });
</script>

@endsection
