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
            display: block; 
            margin-top: 5px;
            font-size: 12px;
            color: #B94A48;
        }

        .parsley-errors-list li {
            list-style-type: none;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <div class="container">    
                <h4 class="bold_font_DS darkcolor_icn letter_spcing_1 text-center mt-4">Sign In</h4><br>
                <form action="" autocomplete="off" id="form" data-parsley-validate>
                    <span class="error notfoundErrors text-danger"></span>
                    <div class="form-group">
                        <label for="email" class="bold_font letter_spcing_1">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email address or username" value="" data-parsley-type="email" data-parsley-trigger="blur" required="">
                        <span class="error emailErrors text-danger"></span>
                        <span class="parsley-errors-list"></span>
                    </div>
                    <div class="form-group">
                        <label for="password" class="bold_font float_left letter_spcing_1">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="" placeholder="Enter your password" data-parsley-trigger="blur" required="">
                        <span class="error passwordErrors text-danger"></span>  
                        <span class="parsley-errors-list"></span>                     
                    </div>
                    <input type="hidden" name="loginPassword" id="loginPassword">
                    <button onclick="check_login()" class="btn btn-lg btn-primary btn-block mt-2" type="button">Sign in</button>                  
                    
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

    <script>
        function encryptdata(str, screetkey) {
            let string = JSON.stringify(str);
            var salt = CryptoJS.lib.WordArray.random(256);
            var iv = CryptoJS.lib.WordArray.random(16);
            var key = CryptoJS.PBKDF2(screetkey, salt, {
                hasher: CryptoJS.algo.SHA512,
                keySize: 64 / 8,
                iterations: 999,
            });

            var encrypted = CryptoJS.AES.encrypt(string, key, {
                iv: iv
            });

            var data = {
                ciphertext: CryptoJS.enc.Base64.stringify(encrypted.ciphertext),
                salt: CryptoJS.enc.Hex.stringify(salt),
                iv: CryptoJS.enc.Hex.stringify(iv),
            };

            var data1 = CryptoJS.enc.Hex.stringify(salt) + CryptoJS.enc.Base64.stringify(encrypted.ciphertext) + CryptoJS.enc.Hex.stringify(iv);

            return data1;
        }

        function check_login() {
            $('#form').parsley().validate();
            if ($('#form').parsley().isValid()) {
                var fd = new FormData();
                var _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var email = $("input[name='email']").val();
                var psw = $("input[name='password']").val();
                var key = "{{ env('CUSTOM_KEY') }}";
                var enc_password = encryptdata(psw,key);            
                var base_url = {!!json_encode(url('/')) !!};
                fd.append('_token', _token);
                fd.append('email', email);
                fd.append('password', enc_password);

                $.ajax({
                url: base_url + '/checklogin',
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {                                           
                    var response = data.trim();                    
                    if (response === 'NotFound') {
                        $('.notfoundErrors').text('Invalid Credentials')
                    } else if (response === 'PassNotFound') {
                        $('.notfoundErrors').text('')
                        $('.passwordErrors').text('Incorrect password')
                    } else {
                        var response = data.trim();
                        var getAaary = response.split('_,');                        
                        if (getAaary[0] === '1') {
                            window.location.href = 'Admin/Dashboard';                        
                        } else if (getAaary[0] === '2') {
                                if (getAaary[1] == 'First') {
                                    window.location.href = 'changePassword/' + getAaary[2];                                    
                                } else if (getAaary[1] == 'Get') {                                  
                                    window.location.href = 'User/Dashboard';                                    
                                } else {
                                    alert('Something Went Wrong');
                                }
                            } else {
                            alert('Something Went Wrong');
                        }

                    }
                },
            });



            }
        }
    </script>
</body>

</html>