<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/style.css') }}">    
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100%;
        }

        .login-form {
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .background-image {
            display: none;
            width: 75%;
            background: url('{{ asset("public/asset/img/background.jpg") }}') no-repeat;
            background-size: cover;
            position: relative;
        }

        .container {
            max-width: 90%;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }

        @media (min-width: 769px) and (max-width: 900px) {
            .login-form {
                width: 50%;
            }

            .background-image {
                display: block;
                width: 50%;
            }
        }

        @media (max-width: 768px) {
            .background-image {
                display: none;
            }
        }

        @media (min-width: 901px) {
            .login-form {
                width: 25%;
            }

            .background-image {
                display: block;
                width: 75%;
            }
        }
    </style>
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
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <div class="container">
                <h4 class="bold_font_DS darkcolor_icn letter_spcing_1 text-left mt-4">Reset Password</h4><br>
                <form action="" autocomplete="off" id="form" data-parsley-validate>
                    <div class="form-group">
                        <label for="password" class="text_lable">Password<span class="mandatory"> *</span></label>
                        <input type="password" class="form-control simplebox" id="pwd" name="pwd" autocomplete="new-password" data-parsley-trigger="blur" required="" data-parsley-length="[8, 16]" data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$" data-parsley-pattern-message="Please include 1 Upper case 1 lower case 1 integer 1 special character and length 8-20." data-parsley-errors-container=".errorspassword" data-parsley-required-message="Password is required." placeholder="Password">
                        <span class="error errorspassword"></span>
                        <span class="passwordErrors text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="cpassword" class="text_lable">Confirm Password<span class="mandatory"> *</span></label>
                        <input type="password" class="form-control simplebox" id="cpassword" name="cpassword" autocomplete="new-password" data-parsley-trigger="blur" required="" data-parsley-length="[8, 16]" data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$" data-parsley-pattern-message="Please include 1 Upper case 1 lower case 1 integer 1 special character and length 8-20." data-parsley-errors-container=".errorscpassword" data-parsley-required-message="Confirm Password is required." placeholder="Confirm Password">
                        <span class="error errorscpassword"></span>
                        <span class="cpasswordErrors text-danger"></span>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="{{$userIds}}">
                    <button onclick="check_details()" class="btn btn-lg btn-primary btn-block mt-2" type="button">Submit</button>

                </form>
            </div>
        </div>
        <div class="background-image"></div>
    </div>

    <script src="{{ asset('public/asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/asset/js/ionicons.js') }}"></script>
    <script src="{{ asset('public/asset/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/asset/js/parsley.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="{{ asset('public/asset/js/SweetAlert_Function.js') }}"></script>
    <script src="{{ asset('public/asset/js/sweetalert.2.10.js') }}"></script>


    <script>
        function check_details() {
            $('#form').parsley().validate();
            if ($('#form').parsley().isValid()) {
                var fd = new FormData();
                var _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var base_url = {!!json_encode(url('/')) !!};
                fd.append('_token', _token);
                fd.append('password', $("#pwd").val());
                fd.append('confirmPassword', $("#cpassword").val());
                fd.append('id', $("#user_id").val());
                $.ajax({
                    url: base_url + '/checkpassword',
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {                        
                        $('.passwordErrors').text('');
                        $('.cpasswordErrors').text('');
                        if (data.errors) {
                            for (const [field, messages] of Object.entries(data.errors)) {
                                messages.forEach(message => {
                                    if (field === 'password') {
                                        $('.passwordErrors').append(`${message}<br>`);
                                    } else if (field === 'confirmPassword') {
                                        $('.cpasswordErrors').append(`${message}<br>`);
                                    } 
                                });
                            }
                        } else {                            
                            var response = data['message'].trim();
                            if (response == 'Done') {
                                var done = response.split('_');
                                reurl = '/';
                                redirect = base_url + reurl                               
                                Message = 'Password updated Successfully. Please Sign in'
                                SuccessAlert(Message, redirect);                              
                            } else if (response == 'User not found') {
                                Message = 'User not found'
                                InfoAlert(Message)
                            } else if (response == 'Invalid data provided') {
                                Message = 'Invalid data provided'
                                InfoAlert(Message)
                            } else {
                                InfoAlert(response)
                            }
                        }

                    },
                });



            }
        }
    </script>
</body>

</html>