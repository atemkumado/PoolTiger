@extends('index')

@section('content')
    <div class="content">
        <div class="profile-card">
            <div class="profile-info">
                <div class="profile-header">
                    <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="profile-image">
                    <div class="" style="display: block">
                        <div class="profile-name">
                            <h4>Mr. Nguyen Thanh Long</h4>
                            <h6>Zalo - Ho Chi Minh City, Viet Nam</h6>
                        </div>
                        <div class="profile-social">
                            <a href="fb.com" class="mr-4" target="_blank"><i class="fa fa-facebook-f"></i></a>
                            <a href="fb.com" class="mr-4" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="fb.com" class="mr-4" target="_blank"><i class="fa fa-instagram"></i></a>
                            <a href="fb.com" class="mr-4"target="_blank"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-4 mr-4">
            <div class="profile-container col-7">
                <div class="profile-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">First name</th>
                            <td>Thu</td>
                        </tr>
                        <tr>
                            <th width="30%">Last name</th>
                            <td>Diep</td>
                        </tr>
                        <tr>
                            <th width="30%">Date of Birth</th>
                            <td>24/05/2000</td>
                        </tr>
                        <tr>
                            <th width="30%">Years Of Experience</th>
                            <td>2</td>
                        </tr>
                        <tr>
                            <th width="30%">Position Title</th>
                            <td>Fresher PHP Developer</td>
                        </tr>
                        <tr>
                            <th width="30%">Top 1</th>
                            <td>PHP</td>
                        </tr>
                        <tr>
                            <th width="30%">Notice</th>
                            <td>PHP</td>
                        </tr>
                        <tr>
                            <th width="30%">Expected Salary</th>
                            <td>12.000.000</td>
                        </tr>
                        <tr>
                            <th width="30%">Salary</th>
                            <td>12.000.000</td>
                        </tr>
                        <tr>
                            <th width="30%">City</th>
                            <td>Ho Chi Minh</td>
                        </tr>
                        <tr>
                            <th width="30%">District</th>
                            <td>10</td>
                        </tr>
                        <tr>
                            <th width="30%"> English Level</th>
                            <td>Good</td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class="container-reference col-5">
                <table class="table table-bordered">
                    <tr>
                        <td class="reference-item">
                            Resume</p>
                            <button type="button" class="btn btn-light btn-view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="reference-item">
                            Assessment Form
                            <button type="button" class="btn btn-light btn-view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="reference-item">
                            Video
                            <button type="button" class="btn btn-light btn-view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="reference-item">
                            Summary
                            <button type="button" class="btn btn-light btn-view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="reference-item">
                           General Knowledge Test
                            <button type="button" class="btn btn-light btn-view">View</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    {{-- ---------------------------------------------------------------- --}}


@stop
