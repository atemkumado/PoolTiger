<div class="statistic">

    @foreach($provinces as $province)
        <li>
            <div class="item">
                <a class="menu-location font-semibold  hover:text-gray-900"
                   type="button" data-bs-toggle="modal" data-bs-target="#talent-modal"
                   data-province_id="{{ $province['id']}}"
{{--                   wire:click="setProvinceId({{$province['id']}})"--}}
                >
                    {{$province['name']}}</a>
                <p class="menu-statistic font-semibold">{{$province['count']}}</p>
            </div>
        </li>
    @endforeach

    <div wire:ignore.self class="modal fade" id="talent-modal" tabindex="1" aria-labelledby="talentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content" style="width: max-content; height: 80%">
                <div class="modal-header" style="background-color: rgba(255,178,126,0.47)">
                    <h6 class="modal-title" style="padding-left: 10px"><strong>Talents</strong></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <strong style="color: black"></strong>X
                    </button>
                </div>
                <div class="modal-body" style="min-height: 400px; height: 80%">

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
                                    <th>Salary</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>


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
        <script>
            console.log("TABLE")
            $('#talent-table', this).DataTable().destroy();
            // Close the modal when the close button or outside modal is clicked

            $(document).ready(function () {
                $('#talent-modal').on('hidden.bs.modal', function () {
                    // Find the DataTable instance within the modal content and destroy it
                    console.log("DESTROY");
                    $('#talent-table', this).DataTable().destroy();
                });
                $('.close, .modal').on('click', function() {
                    $('#talent-modal').css('display', 'none');
                });

// Prevent modal from closing when clicking inside the modal content
                $('.modal-content').on('click', function(event) {
                    event.stopPropagation();
                });


                $('.menu-location').on('click', function (e) {
                // $('#talent-modal').on('shown.bs.modal', function (e) {
                    const request = $(this).data('province_id') ?? 0;
                    const route = "{{ route('talents.get', ['data' => ':data']) }}"
                    loadData(route, request);
                });
                $('.view-talents').on('click', function (e) {
                    // $('#talent-modal').on('shown.bs.modal', function (e) {
                    const route = "{{ route('talents.view', ['data' => ':data']) }}"
                    loadData(route);
                });
                function loadData(route, request = null){
                    console.log(route);
                    $('#talent-table').css('display', 'block');
                    $('#talent-table').DataTable({
                        "order": [[0, 'asc']],
                        "columnDefs": [{
                            "targets": 0, // Targeting the first column
                            "data": null, // Use null if you're adding custom content rather than binding to data
                            "render": function(data, type, row, meta) {
                                // Use 'meta.row' to get the row index and add 1 to start numbering from 1
                                return meta.row + 1;
                            }
                        }],
                        "processing": true,
                        "serverSide": false, // Enable server-side processing
                        "ajax": {
                            "url": route.replace(':data', request), // URL to fetch data from
                            "type": "GET",
                            "dataSrc": "data"
                        },
                        "columns": [
                            { "data": "no" },
                            { data: 'name', name: 'name', render: function (data, type, row) {
                                    return '<a href="/detail/' + row.id + '">' + data + '</a>';
                                }},
                            { "data": "email" },
                            { "data": "phone" },
                            { "data": "company_name" },
                            { "data": "province_name" },
                            { "data": "experience" },
                            { "data": "skill_name" },
                            { "data": "position_name" },
                            { "data": "salary" },
                        ]
                    });
                }

            })
        </script>
    @endpush
</div>



