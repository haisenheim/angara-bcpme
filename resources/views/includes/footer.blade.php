       <!-- Bottom navigation menu -->
       <div class="mainnav__bottom-content border-top pb-2">
        <ul id="mainnav" class="mainnav__menu nav flex-column">
            <li class="nav-item">

                <a id="btn-logout" href="#" class="nav-link">
                    <i class="demo-pli-unlock fs-5 me-2"></i>
                    <span class="nav-label ms-1">Se déconnecter</span>
                </a>
            </li>
        </ul>
        <div style="display: none">
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                
            </form>
        </div>
    </div>
    <!-- End - Bottom navigation menu -->

</div>
</nav>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- END - MAIN NAVIGATION -->
 <!-- SIDEBAR -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <aside class="sidebar h-100" style="padding-bottom:50px">
            <div class="sidebar__inner scrollable-content">
                <div class="sidebar__wrap" style="">
                    @yield('sidebar')
                </div>
            </div>
        </aside>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - SIDEBAR -->


</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- END - PAGE CONTAINER -->

<!-- SCROLL TO TOP BUTTON -->
<div class="scroll-container">
<a href="#root" class="scroll-page rounded-circle ratio ratio-1x1" aria-label="Scroll button"></a>
</div>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- END - SCROLL TO TOP BUTTON -->




<!-- OFFCANVAS [ DEMO ] -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<div id="_dm-offcanvas" class="offcanvas" tabindex="-1">

<!-- Offcanvas header -->
<div class="offcanvas-header">
<h5 class="offcanvas-title">Offcanvas Header</h5>
<button type="button" class="btn-close btn-lg text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<!-- Offcanvas content -->
<div class="offcanvas-body">
<h5>Content Here</h5>
<p class="lh-base">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente eos nihil earum aliquam quod in dolor, aspernatur obcaecati et at. Dicta, ipsum aut, fugit nam dolore porro non est totam sapiente animi recusandae obcaecati dolorum, rem ullam cumque. Illum quidem reiciendis autem neque excepturi odit est accusantium, facilis provident molestias, dicta obcaecati itaque ducimus fuga iure in distinctio voluptate nesciunt dignissimos rem error a. Expedita officiis nam dolore dolores ea. Soluta repellendus delectus culpa quo. Ea tenetur impedit error quod exercitationem ut ad provident quisquam omnis! Nostrum quasi ex delectus vero, facilis aut recusandae deleniti beatae. Qui velit commodi inventore.</p>
</div>

</div>
<script nomodule src="{{ asset('js/ionicons.js') }}"></script>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- END - OFFCANVAS [ DEMO ] -->
<!-- JAVASCRIPTS -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<link rel="stylesheet" href="{{ asset('assets/vendors/zangdar/zangdar.min.css') }}">
<!-- Popper JS [ OPTIONAL ] -->
<script src="{{ asset('assets/vendors/popperjs/popper.min.js') }}" defer></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap JS [ OPTIONAL ] -->
<script src="{{ asset('assets/vendors/bootstrap/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('assets/vendors/zangdar/zangdar.min.js') }}"></script>
<!-- Nifty JS [ OPTIONAL ] -->
<script src="{{ asset('assets/js/nifty.js') }}" defer></script>

<script>
    $('#btn-logout').click(function(){
        $('#logout-form').submit();
    })
</script>
@yield('scripts')
@stack('scripts')

</body>

</html>
