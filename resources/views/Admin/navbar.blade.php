<style>
.dropdown-menu-md {
    min-width: 188px;
}
</style>
 
<nav class="navbar navbar-expand-md navbar-light bg-light desktop-nav">
    <a class="navbar-brand pb-2" href="#">
        <label class="dms_short bold_font">           
            ADMIN PORTAL
        </label>       
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse ul_nav" id="navbarNavDropdown">
        <ul class="navbar-nav" id="menus_patch" style="margin-left: 30px;">          
            <li class="nav-item dropdown">
                <a class="nav-link  cursor_pointer bold_600 webkit_box" href="{{ url('Admin/Dashboard')}}">
                    <div class="nav_iconblack person-outline"></div>
                    <span class="top_nav_aspan">Users</span>
                </a>
            </li>           
            <li class="nav-item dropdown drop_pos">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('public/asset/img/Sample_User_Icon.png') }}"
                        class="usermini_pic icn_with_shadow cursor_pointer">
                    <span style="font-weight: 600;">{{ session('username') }}</span> </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-md pad0 borderLogout"
                    aria-labelledby="navbarDropdown">
                    <div class="dropdown-inner ">
                        <ul class="link-list link_style_none">
                            <li>
                                <a href="{{URL::to('/logout')}}" class="subhead_font webkit_box">
                                    <div class="icon_loginout power-outline"></div>
                                    <span class="a_second">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
 
                </div>
            </li>
        </ul>
    </div>
</nav>