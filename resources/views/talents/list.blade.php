@extends('index')

@section('content')
    <div class="content">
        @include('components.modal-list')
        <div class="row content-row">
            <div class="container-filter col-sm-4">
                <h4><strong>Search by:</strong></h4>
                <!-- Display the success message if any -->
                @if (session('success'))
                    <div class="form-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Display the form errors if any -->
                @if ($errors->any())
                    <div class="form-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Create the form using Laravel collective -->
                {!! Form::open(['route' => 'talents.list', 'method' => 'get', 'class' => 'form-horizontal ml-2']) !!}

                <div class="form-group row">
                    {!! Form::label('province', 'City', ['class' => 'col-sm-4 form-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('province', $filter['province'] ?? NULL, $selected['province'] ?? NULL, [
                            'class' => 'form-select',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('skill', 'Top 1 Language', ['class' => 'form-label col-sm-4']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('skill', $filter['skill'] ?? NULL, $selected['skill'] ?? NULL, [
                            'class' => 'form-select col-sm-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('experience', "Years' Experience", ['class' => 'form-label col-sm-4']) !!}
                    <div class="col-sm-8">
                        {!! Form::number('experience', $selected['experience'] ?? null, [
                            'class' => 'form-input',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('position', 'Position Level', ['class' => 'form-label col-sm-4']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('position', $filter['position'] ?? NULL, $selected['position'] ?? NULL, [
                            'class' => 'form-select col-sm-8',
                            'placeholder' => 'Select an option',

                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('english', 'English level', ['class' => 'form-label col-sm-4']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('english', $filter['english'] ?? NULL, $selected['english'] ?? NULL, [
                            'class' => 'form-select col-sm-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('salary', 'Salary', ['class' => 'form-label col-sm-4']) !!}
                    <div class="col-sm-8">
                        {!! Form::number('salary', $selected['salary'] ?? NULL, [
                            'class' => 'form-input',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                </div>


                <button class="form-button"><i class="fa fa-search"></i></button>
                {!! Form::close() !!}

            </div>
            @if (request()->route()->getName() == 'talents.list')
                <div class="container-list col-sm-7">
                    <ul class="list-unstyled">
                        <div class="list-title">
                            <h4><strong>Available Talents: </strong>
                                <span style="color: #c43e1c;" class="ml-3"> {{count($talents ?? []) }}</span>
                            </h4>
                        </div>

                        @if(count($talents ?? []) > 0)
                            @php
                                $count = 3;
                            @endphp
                            @foreach(collect($talents) as $talent)

                                @if($count == 0)
                                    @break;
                                @else
                                    @php
                                        $count--;
                                    @endphp
                                @endif
                                <li class="talent mt-3">
                                    <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="talent-avatar">
                                    <a class="talent-btn "
                                       href="{{ route('talents.detail', ['id'=>$talent['id']]) }}"><i
                                            class="fa fa-search"></i></a>
                                    <div class="talent-body">
                                        <h5 class="mt-0 mb-1"><strong>{{$talent['name']}}</strong></h5>
                                        <div class="talent-company">
                                            {{$talent->company->name}} - {{$talent->company->province->name}}, Viet Nam
                                        </div>

                                    </div>
                                </li>

                            @endforeach
                            <li class="talent">
                                <div class="talent-body mt-4">
                                    <a href ="" type="button" data-bs-toggle="modal"
                                       data-bs-target="#modal-list">
                                        View more ...
                                    </a>
                                </div>

                            </li>
                        @else
                            <li class="talent">
                                <div class="talent-body mt-4">
                                    Empty
                                </div>

                            </li>
                        @endif

                    </ul>
                </div>
            @endif

        </div>
        <div class="data-show btn" data-toggle="modal" data-target="#changeBetRate" style="" id="select "
             data-ans_id="1"
             data-ans_bet_rate="1">

            <div class="" align="center" style="color:#fff;">
        <span id="" class="" text-align="center" style="color:#000000;">
            1
        </span>
            </div>
        </div>
        <div style="color:black" class="modal fade betForm" id="changeBetRate" role="dialog" aria-hidden="true"
             style="display: none;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header m-head" style="  background:#4267B2">
                        <button type="button" class="close" data-dismiss="modal" style="color: #ffffff">&times;</button>
                        <h4 class="modal-title" style="color: white;"> &nbsp; Change Bet Rate</h4>
                    </div>

                    <div class="modal-body" style="padding: 2% !important">
                        <div class="">
                            <div role="form" class="register-form">

                                <div class="row">
                                    <div class="col-md-6 col-md-6 col-md-6; text-align: right;">
                                        <div class="form-group">
                                            <label style="text-align: center;width: 100%;color:#07000a;">New Bet
                                                Rate</label>
                                            <input style="text-align: center;width: 100%;" type="text"
                                                   name="change_bet_rate" id="change_bet_rate"
                                                   class="form-control input-md" placeholder="Bet Rate" tabindex="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6"><input type="submit" id="changeBetSubmit" value="Update"
                                                                 class="btn  btn-block btn-md" tabindex="7"
                                                                 style="  background:#078d34;color: #fff"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

{{-- ---------------------------------------------------------------- --}}

@push('scripts')
    <script>
        console.log(@json($talents ?? []));
        $(document).on('show.bs.modal', '#changeBetRate', function () {
            console.log("----------------");
        });
    </script>
    @if (request()->route()->getName() == 'talents.filter')
        <script>
            $(document).ready(function () {
                // var path = window.location.pathname;
                // console.log(path);
                $('.content').css("background-image", 'url("http://127.0.0.1:8000/css/Talent Pool visual.jpg")');
            });
        </script>
    @else

    @endif
@endpush
