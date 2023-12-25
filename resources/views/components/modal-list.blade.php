<div wire:ignore.self class="modal fade" id="modal-list" tabindex="-1" aria-labelledby="talentModalLabel"
     aria-hidden="true" id="modalblue">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="width: max-content; height: 80%">
            <div class="modal-header" style="background-color: rgba(255,178,126,0.47)">
                <h6 class="modal-title" style="padding-left: 10px"><strong>Talents</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                        style="color: black"></span>X
                </button>
            </div>
            <div class="modal-body">
                    @if(!is_null($talents))
                        <table id="talent-table" class="table table-striped table-bordered" style="width:100%">
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
                            @foreach($talents as $id => $data)
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
            <div class="modal-footer" style="min-height: 40px">
                {{--                <button type="button" class="btn btn-secondary" wire:click="closeModal"--}}
                {{--                        data-bs-dismiss="modal">Close--}}
                {{--                </button>--}}
                {{--                <button type="submit" class="btn btn-primary">Save</button>--}}
            </div>
        </div>
    </div>
</div>
@push('datatables')
    <script>
        console.log("TABLE")
        $(document).ready(function () {
            $('#modal-list').on('shown.bs.modal', function (e) {
                $('#talent-table').DataTable().destroy();
                var table = $('#talent-table').DataTable({
                    lengthChange: false,
                    buttons: [
                        {
                            extend: 'csv',
                            split: ['pdf', 'excel'],
                        }
                    ]
                });

            });
        })
    </script>
@endpush
