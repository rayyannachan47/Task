@extends('Layout.admin')
@section('content')
@include('Admin.navbar')
<style>
    .modal-dialog {
        width: 80%;
        max-width: 500px;
        height: 500px;
        margin: auto;
    }

    .modal-content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .modal-body {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .modal-body img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
 
</style>

<div id="sidebar_paddings">
    <div class="container-fluid">
        <div class="formHolder">
            <div class="alignleft m_7 mb-2">
                <div class="d_flex">
                    <label class="bold_font_DS font_25 margin_bottom_10">Users List</label>
                </div>
            </div>
            <div class="alignright m_7 mb-2">
                <a href="{{ url('Admin/add_user') }}">
                    <button class="btn btn-primary btn-block pad_btn width_auto margin-left_10 font_12" id="createnewid">
                        <div class="iconwhite add-circle-outline"></div> Create User
                    </button>
                </a>
            </div>
            <div style="clear: both;"></div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="client-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="1%">Sr.no</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Conatct</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="imageModalLabel">Photo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Image" style="width: 100%; max-height: 500px; object-fit: contain;" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if (Session::has('error'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ Session::get("error") }}'
        });
    });
</script>
@endif

<script>
    $(document).on('click', 'a[data-toggle="modal"]', function() {
        var imageUrl = $(this).data('image-url');
        $('#modalImage').attr('src', imageUrl);
    });
</script>

<script>
    $(document).ready(function() {
        var path = {!!json_encode(url('/')) !!};
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        var table = $('#client-table').DataTable({
            "bJQueryUI": false,
            "scrollX": true,
            "serverSide": true,
            "searching": true,
            "paging": true,
            "info": true,
            "ajax": {
                url: path + '/Admin/show_user_data',
                type: 'post',
                data: {
                    _token: CSRF_TOKEN
                },
            },
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'dob',
                    name: 'dob'
                },
                {
                    data: 'contact',
                    name: 'contact'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'images',
                    name: 'images'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

        function checkSessionAndReload() {
            $.ajax({
                url: path + '/checksession',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN
                },
                success: function(response) {
                    if (response.isLoggedIn) {
                        table.ajax.reload(null, false);
                    } else {
                        window.location.href = path + '/';
                    }
                },
                error: function() {
                    window.location.href = path + '/';
                }
            });
        }
        setInterval(checkSessionAndReload, 3000);
    });
</script>

<script>
    function deleteUser(id, event) {
        event.preventDefault();
        if (id == "") {
            Message = 'User not found'
            InfoAlert(Message)
            return;
        }
        var base_url = {!!json_encode(url('/')) !!};       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + '/Admin/delete_user/' + id,
                    type: 'get',
                    success: function(data) {
                        var response = data['message'].trim();
                        if (response == 'Done') {
                            Message = 'User Deleted Successfully'
                            DeleteAlert(Message)
                        } else if (response == 'User not found') {
                            Message = 'User not found'
                            InfoAlert(Message)
                        } else if (response == 'Invalid data provided') {
                            Message = 'Invalid data provided'
                            InfoAlert(Message)
                        } else {
                            InfoAlert(response)
                        }
                    },
                    complete: function() {
                        $('#loading-image').hide();
                    }
                })
            }
        })
    }
</script>

@endsection