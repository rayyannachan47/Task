@extends('Layout.admin')
@section('content')
@include('Admin.navbar')


<style>
    .parsley-errors-list {
        position: absolute;
        top: 100%;
        left: -10;
        margin-top: -13px;
        font-size: 12px;
        color: #B94A48;
    }

    .parsley-errors-list li {
        list-style-type: none;
    }
</style>

<div id="sidebar_paddings">
    <div class="container-fluid">
        <div class="formHolder">
            <div class="d_flex">
                <label class="bold_font_DS font_25 margin_bottom_10">Add User</label>
            </div>
            <div class="clear"></div>
            <form class="form-horizontal" autocomplete="off" action="#" name="user_form" id="user_form" enctype="multipart/form-data" data-parsley-validate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username" class="text_lable">Name<span class="mandatory">*</span></label>
                            <input type="text" data-parsley-trigger="blur" class="form-control simplebox" id="username" name="username" data-parsley-trigger="blur" required="" data-parsley-errors-container=".errorsusername" data-parsley-pattern="^[a-zA-Z]+(?:\s[a-zA-Z]+)*$" data-parsley-required-message="Name is required." placeholder="Add Name">
                            <span class="error errorsusername"></span>
                            <span class="nameerror text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="text_lable">Email<span class="mandatory">*</span></label>
                            <input type="email" class="form-control simplebox" id="email" name="email" autocomplete="nope" data-parsley-type="email" data-parsley-trigger="blur" required="" data-parsley-required-message="Email is required." data-parsley-errors-container=".errorsemail" placeholder="Add Email">
                            <span class="error errorsemail"></span>
                            <span class="emailErrors text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="text_lable">Password<span class="mandatory">*</span></label>
                            <input type="password" class="form-control simplebox" id="pwd" name="pwd" autocomplete="new-password" data-parsley-trigger="blur" required="" data-parsley-length="[8, 16]" data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$" data-parsley-pattern-message="Please include 1 Upper case 1 lower case 1 integer 1 special character and length 8-20." data-parsley-errors-container=".errorspassword" data-parsley-required-message="Password is required." placeholder="Add Password">
                            <span class="error errorspassword"></span>
                            <span class="passwordErrors text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dob" class="text_lable">Date of Birth<span class="mandatory">*</span></label>
                            <input type="date" data-parsley-trigger="blur" class="form-control simplebox" id="dob" name="dob" data-parsley-trigger="blur" required="" data-parsley-errors-container=".errorsdob"
                            data-parsley-required-message="Date of birth is required."  max="<?php echo date('Y-m-d'); ?>">
                            <span class="error errorsdob"></span>
                            <span class="dateerror text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="text_lable">Address<span class="mandatory">*</span></label>                           
                            <textarea id="address" class="form-control simplebox"  name="address" rows="4" cols="50" data-parsley-pattern="^[a-zA-Z0-9\s,.-]+$" data-parsley-pattern-message="Address should not contain special characters."  required 
                            data-parsley-required-message="Address is required."  data-parsley-errors-container=".errorsaddress"></textarea>
                            <span class="error errorsaddress"></span>
                            <span class="addresserror text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact" class="text_lable">Contact<span class="mandatory">*</span></label>
                            <input type="text" data-parsley-trigger="blur" class="form-control simplebox" id="contact" name="contact" data-parsley-trigger="blur" required="" data-parsley-errors-container=".errorscontact" data-parsley-required-message="Contact is required." data-parsley-pattern="^\d{10}$" placeholder="Add Contact">
                            <span class="error errorscontact"></span>
                            <span class="contacterror text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo" class="text_lable">Photo<span class="mandatory">*</span></label>
                            <input type="file" data-parsley-trigger="blur" class="form-control simplebox" id="photo" name="photo" data-parsley-trigger="blur" required="" accept=".png,.jpg,.jpeg" data-parsley-required-message="File is required." onchange="validateFile(this)">                            
                            <span class="fileerror text-danger"></span>
                            <div id="imagePreview" style="margin-top: 10px; display: none;">
                                <img id="previewImg" src="" alt="Image Preview" style="width:100px; height: 100px;">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group float_btn_r">
                    <div class="row pad_0_15">
                        <a href="{{ url('SuperAdmin/showAdmin') }}">
                            <button type="button" class="btn btn-primary btn-block pad_btn width_auto margin-left_10 font_12" style="padding-left:0;">
                                <div class="iconwhite arrow-back-outline"></div>Back
                            </button>
                        </a>
                        <button class="btn btn-primary btn-block pad_btn width_auto margin-left_10 font_12" type="button" onclick="submit_form()" name="submit_id" id="btn_id">
                            <div class="iconwhite checkmark-outline"></div>Submit
                        </button>
                        <img src="{{ asset('public/asset/img/loader_t.gif') }}" id="client_loader" style="width: 30px;display:none;">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function submit_form() {
        $('input').parsley();
        var isValid = true;
        $('#user_form').each(function() {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            var base_url = {!!json_encode(url('/')) !!};
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var fileInput = document.getElementById('photo');
            var formData = new FormData();
            formData.append('name', $("#username").val());
            formData.append('email', $("#email").val());
            formData.append('password', $("#pwd").val());
            formData.append('dob', $("#dob").val());
            formData.append('contact', $("#contact").val());
            formData.append('address', $("#address").val());
            formData.append('photo', fileInput.files[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                url: base_url + '/Admin/add_user_code',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {                              
                    $('.emailErrors').text('');
                    $('.passwordErrors').text('');
                    $('.nameerror').text('');
                    $('.dateerror').text('');
                    $('.addresserror').text('');
                    $('.contacterror').text('');
                    $('.fileerror').text('');
                    if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            messages.forEach(message => {                                
                                if (field === 'email') {
                                    $('.emailErrors').append(`${message}<br>`);
                                } else if (field === 'password') {
                                    $('.passwordErrors').append(`${message}<br>`);
                                }else if (field === 'name') {
                                    $('.nameerror').append(`${message}<br>`);
                                }else if (field === 'dob') {
                                    $('.dateerror').append(`${message}<br>`);
                                }else if (field === 'address') {
                                    $('.addresserror').append(`${message}<br>`);
                                }else if (field === 'contact') {
                                    $('.contacterror').append(`${message}<br>`);
                                }else if (field === 'photo') {
                                    $('.fileerror').append(`${message}<br>`);
                                }
                            });
                        }
                    } else {                        
                        var response = data['message'].trim();
                        if (response == 'Done') {
                            var done = response.split('_');
                            reurl = '/Admin/Dashboard/';
                            redirect = base_url + reurl
                            Message = 'User Created Successfully'
                            SuccessAlert(Message, redirect)
                        } else {
                            Message = 'Something Went Wrong'
                            InfoAlert(Message)
                        }
                    }
                },
            })
        }
    }

    function validateFile(input) {
        const file = input.files[0];
        const allowedExtensions = ["jpg", "jpeg", "png"];
        const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
        const errorSpan = document.querySelector('.fileerror');
        const preview = document.getElementById('previewImg');
        const previewContainer = document.getElementById('imagePreview');

        if (file) {
            const fileExtension = file.name.split(".").pop().toLowerCase();
            
            errorSpan.textContent = '';
            preview.style.display = 'none'; // Hide preview initially

            // Validate file extension
            if (!allowedExtensions.includes(fileExtension)) {
                const message = 'Please select a file with a valid extension (JPG, JPEG, PNG).';
                InfoAlert(message);
                input.value = ""; // Clear the file input
                previewContainer.style.display = 'none'; // Hide the preview container
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                const message = 'File size must be less than 2 MB.';
                InfoAlert(message);
                input.value = ""; // Clear the file input
                previewContainer.style.display = 'none'; // Hide the preview container
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Show the preview image
                previewContainer.style.display = 'block'; // Show the preview container
            };
            reader.readAsDataURL(file);
        } else {
            // No file selected, hide preview and clear error
            preview.src = '';
            preview.style.display = 'none'; // Hide the preview image
            previewContainer.style.display = 'none'; // Hide the preview container
            errorSpan.textContent = ''; // Clear error message
        }
    }

</script>


@endsection