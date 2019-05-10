@extends('layouts.app')
@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="row">
                        @include('layouts.side-menu')
                        <div class="col-md-9 py-4 pl-lg-0">

                            <div class="mt-0">
                                <div class="col-md-12">
                                    <div class="questtions-label page-heading">Spotted on Test</div>
                                    <div class="topic_cards">

                                        @if($spotteds->count() > 0)
                                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach($spotteds as $key_card => $card)
                                                        <div class="carousel-item p-3 {{ $key_card == 0 ? 'active' : '' }}">
                                                            <div class="flip-card">
                                                                <div class="flip-card-inner">
                                                                    <input type="hidden" value="{{ $card->id }}" class="card_id">
                                                                    <input type="hidden" value="{{ $card->spotted_id }}" class="spotted_id">
                                                                    <div class="flip-card-front">
                                                                        {!! !empty($card->image_question) ? '<div class="top-card with_img"><img src="'.asset('uploads/'.$card->image_question).'" height="150"></div>' : '<div class="top-card"> </div>' !!}
                                                                        <div class="middle-card">{{ $card->question }}</div>
                                                                        <div class="bottom-card">{!! $card->citation ? '<a href="javascript:void(0)" data-toggle="modal" data-target="#citationModal" data-content="'.$card->citation.'">Citation information</a>' : '' !!}</div>

                                                                    </div>
                                                                    <div class="flip-card-back">
                                                                        {!! !empty($card->image_answer) ? '<div class="top-card with_img"><p class="question">'.$card->question.'</p><img src="'.asset('uploads/'.$card->image_answer).'" height="150"></div>' : '<div class="top-card"> <p class="question">'.$card->question.'</p></div>' !!}
                                                                        <div class="middle-card">
                                                                            <div class="answer"><i class="fas fa-arrow-right"> </i>&nbsp;{{ $card->answer }}</div>
                                                                        </div>
                                                                        <div class="bottom-card">{!! $card->citation ? '<a href="javascript:void(0)" data-toggle="modal" data-target="#citationModal" data-content="'.$card->citation.'">Citation information</a>' : '' !!}</div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                                <div class="navigations mt-3">
                                                    <button class="bg-transaparent left-btn disabled"><i class="fas fa-arrow-left"></i></button>

                                                    <button class="btn btn-dark toggle-card mx-3">Show Answer</button>

                                                    <button class="bg-transaparent right-btn {{ $spotteds->count() == 1 ? 'disabled' : '' }}"><i class="fas fa-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-danger" role="alert">
                                                {{ 'No Spotted on Test.' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="citationModal" tabindex="-1" role="dialog" aria-labelledby="citationModal"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="citationModalLongTitle">Citation Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.carousel').carousel({
            interval: false,
            wrap:false
        });
        $('.navigations button.left-btn').on('click', function(){
            $('a.carousel-control-prev').click();
            $('.topic_cards .carousel-item .flip-card').removeClass('active');
            var text = 'Show Answer';
            $('.topic_cards .carousel button.toggle-card').text(text);
        });
        $('.navigations button.right-btn').on('click', function(){
            $('a.carousel-control-next').click();
            $('.topic_cards .carousel-item .flip-card').removeClass('active');
            var text = 'Show Answer';
            $('.topic_cards .carousel button.toggle-card').text(text);
        });
        $('.topic_cards .carousel button.toggle-card').on('click', function(){
            $('.topic_cards .carousel-item.active .flip-card').toggleClass('active');
            var text = $(this).text() == 'Show Answer' ? 'Show Question' : 'Show Answer';
            $(this).text(text);
        });
        $('.topic_cards .carousel .navigations .fav-btn').on('click', function () {
            var card_id = $('.topic_cards .carousel-item.active input.card_id').val();
            var spotted_id = $('.topic_cards .carousel-item.active input.spotted_id').val();

            $.ajax({
                url: '{{ route('favorite.toggle') }}',
                data: {card_id: card_id, spotted_id: spotted_id, _token: '{{ csrf_token() }}'},
                type: 'POST',
                success: function (response) {
                    if(response.success != ''){
                        $('.topic_cards .carousel .navigations .fav-btn').addClass('heart');
                        $('.topic_cards .carousel .navigations .fav-btn i').addClass('fas');
                        $('.topic_cards .carousel .navigations .fav-btn i').removeClass('far');
                    }else{
                        $('.topic_cards .carousel .navigations .fav-btn').removeClass('heart');
                        $('.topic_cards .carousel .navigations .fav-btn i').addClass('far');
                        $('.topic_cards .carousel .navigations .fav-btn i').removeClass('fas');
                    }
                    $('.topic_cards .carousel-item.active input.spotted_id').val(response.success);
                }
            });

        })

        $('.carousel').on('slid.bs.carousel', function () {
            var spotted_id = $('.topic_cards .carousel-item.active input.spotted_id').val();
            if(spotted_id != ''){
                $('.topic_cards .carousel .navigations .fav-btn').addClass('heart');
                $('.topic_cards .carousel .navigations .fav-btn i').addClass('fas');
                $('.topic_cards .carousel .navigations .fav-btn i').removeClass('far');
            }else{
                $('.topic_cards .carousel .navigations .fav-btn').removeClass('heart');
                $('.topic_cards .carousel .navigations .fav-btn i').addClass('far');
                $('.topic_cards .carousel .navigations .fav-btn i').removeClass('fas');
            }

            var $current = $('.topic_cards .carousel-item.active');
            var nextElem = $current.next();
            if(!nextElem.length) {
                $('.topic_cards .carousel button.right-btn').addClass('disabled');
            }else{
                $('.topic_cards .carousel button.right-btn').removeClass('disabled');
            }

            var prevElem = $current.prev();
            if(!prevElem.length) {
                $('.topic_cards .carousel button.left-btn').addClass('disabled');
            }else {
                $('.topic_cards .carousel button.left-btn').removeClass('disabled');
            }
        });

        $('#citationModal').on('show.bs.modal', function (e) {
            $('#citationModal .modal-body').html($(e.relatedTarget).data('content'));
        });
    </script>
@endsection