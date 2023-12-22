<div class="statistic">

    @foreach($provinces as $province)
        <li>
            <div class="item">
                <a class="menu-location font-semibold  hover:text-gray-900"
                   type="button" data-bs-toggle="modal" data-bs-target="#modal-list-province"
                   wire:click="setProvinceId({{$province['id']}})"
                >
                    {{$province['name']}}</a>
                <p class="menu-statistic font-semibold">{{$province['count']}}</p>
            </div>
        </li>
    @endforeach

    <div wire:ignore.self class="modal fade" id="modal-list-province" tabindex="1" aria-labelledby="talentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="width: max-content; height: 80%">
                <div class="modal-header" style="background-color: rgba(255,178,126,0.47)">
                    <h6 class="modal-title" style="padding-left: 10px"><strong>Talents</strong></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <strong style="color: black"></strong>X
                    </button>
                </div>
                <div class="modal-body" style="min-height: 400px; height: 80%">
                    <div wire:init="setProvinceId">
                        @if(!is_null($list))
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Company</th>
                                    <th>Province</th>
                                    <th>Experience</th>
                                    <th>Skill</th>
                                    <th>Position</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $id => $data)
                                    <tr>
                                        <th>{{$id}}</th>
                                        <th>{{$data["name"]}}</th>
                                        <th>{{$data["email"]}}</th>
                                        <th>{{$data["phone"]}}</th>
                                        <th>{{$data["company"]["name"]}}</th>
                                        <th>{{$data["province_name"]}}</th>
                                        <th>{{$data["experience"]}}</th>
                                        <th>{{$data["skill_name"]}}</th>
                                        <th>{{$data["position_name"]}}</th>
                                    </tr>
                                @endforeach
                            </table>

                        @else
                            <em>Loading.....</em>
                        @endif
                    </div>
                </div>
                <div class="modal-footer" style="min-height: 40px">
                    {{--                                    <button type="button" class="btn btn-secondary" wire:click="closeModal"--}}
                    {{--                                            data-bs-dismiss="modal">Close--}}
                    {{--                                    </button>--}}
                    {{--                                    <button type="submit" class="btn btn-primary">Save</button>--}}
                </div>
            </div>
        </div>
    </div>

    @push('datatables')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link src="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
        <link src="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
        <script>

            console.log("TABLE")
            $(document).ready(function () {
                $('#modal-list-province').on('shown.bs.modal', function (e) {
                    console.log("CMM")

                    var table = $('#example').DataTable({
                        lengthChange: false,
                        buttons: [
                            {
                                extend: 'csv',
                                split: ['pdf', 'excel'],
                            }
                        ]
                    });

                });
                $('#modal-list-province').on('hidden.bs.modal', function (e) {
                    console.log("CMM")

                    var table = $('#example').DataTable().destroy();

                });
            })
        </script>
    @endpush
</div>



