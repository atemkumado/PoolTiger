@extends('index')

@section('content')
    <div class="content">

        <div class="row content-row">
            <div class="container-filter col-sm-4">
                <h4 style="font-size: larger; margin-bottom: 10px"><strong>Search by:</strong></h4>
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
                                $count = 5;
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
                                    <div class="talent-body" style ="{{ $talent['company_name'] ? '' : '
                                        display: flex; align-items: center;'}}">
                                        <h5 class="mt-0 mb-1" style="  margin: auto;"><strong>{{$talent['name']}}</strong></h5>
                                        @if($talent['company_name'])
                                            <div class="talent-company">
                                                {{$talent['company_name']}}
                                                @if($talent['province_company_name'])
                                                - {{$talent['province_company_name']}}
                                                @endif
                                                , Viet Nam
                                            </div>
                                        @endif

                                    </div>
                                </li>

                            @endforeach
                            <li class="talent">
                                <div class="talent-body mt-4">
                                    <a href="" type="button" data-bs-toggle="modal" class="view-talents"
                                       data-bs-target="#talent-modal">
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
    </div>

@endsection

{{-- ---------------------------------------------------------------- --}}

@push('scripts')
    @if (request()->route()->getName() == 'talents.filter')
        <script>
            $(document).ready(function () {
                $('.content').css("background-image", 'url("{{ asset('css/Talent Pool visual.jpg') }}")');
            });
        </script>
    @endif

@endpush
