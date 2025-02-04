@include('includes.header')
<?php
    $active = \Illuminate\Support\Facades\Session::get('active');
?>
     <!-- Navigation Category -->
     <div>
        <ul class="mainnav__menu nav flex-column">
            <li class="nav-item">
                <a href="{{ route('cooperative.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="pli-monitor-analytics fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Tableau de board</span>
                </a>
            </li>
        </ul>
     </div>
     <div class="mainnav__categoriy py-3">
         <h6 class="mainnav__caption mt-0 px-3 fw-bold">Activite</h6>
         <ul class="mainnav__menu nav flex-column">
            <li class="nav-item">
                <a href="{{ route('cooperative.entrees.create') }}" class="nav-link mininav-toggle {{ $active==2?'active':'' }}"><i class="pli-full-view fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Nouvelle entrée en stock</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('cooperative.sorties.create') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}"><i class="pli-maximize fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Nouvelle sortie de stock</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('cooperative.entrees.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="pli-arrow-inside fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Historique des entrées</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('cooperative.sorties.index') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}"><i class="pli-arrow-outside fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Historique des sorties</span>
                </a>
            </li>

            


         </ul>
     </div>
     <!-- END : Navigation Category -->

     <div class="mainnav__categoriy py-3">
        <h6 class="mainnav__caption mt-0 px-3 fw-bold">Finance</h6>
        <ul class="mainnav__menu nav flex-column">
        
           <li class="nav-item">
               <a href="#" class="nav-link mininav-toggle {{ $active==6?'active':'' }}"><i class="pli-coins-3 fs-3 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Nouvel appel de fonds</span>
               </a>
           </li>

           <li class="nav-item">
                <a href="#" class="nav-link mininav-toggle {{ $active==7?'active':'' }}"><i class="pli-coins fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Tous les appels de fonds</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link mininav-toggle {{ $active==8?'active':'' }}"><i class="pli-calculator fs-3 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Requetes des agents</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="mainnav__categoriy py-3">
        <h6 class="mainnav__caption mt-0 px-3 fw-bold">Systeme</h6>
        <ul class="mainnav__menu nav flex-column">
            <li class="nav-item">
                <a href="{{ route('cooperative.members.index') }}" class="nav-link mininav-toggle {{ $active==9?'active':'' }}"><i class="pli-farmer fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Membres</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('cooperative.agents.index') }}" class="nav-link mininav-toggle {{ $active==10?'active':'' }}"><i class="pli-worker fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Agents de terrain</span>
                </a>
            </li>
            <!-- Link with submenu -->
           <li class="nav-item has-sub">
               <a href="#" class="mininav-toggle nav-link {{ ($active>700&&$active<800)?'active':'' }}"><i class="pli-gear fs-2 me-2"></i>
                   <span class="nav-label ms-1">Parametres</span>
               </a>
               <!-- Settings submenu list -->
               <ul class="mininav-content nav collapse">

                  <li class="nav-item">
                      <a href="{{ route('cooperative.entrepots.index') }}" class="nav-link {{ $active==701?'active':'' }}">Entrepots</a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('cooperative.villages.index') }}" class="nav-link {{ $active==702?'active':'' }}">Villages</a>
                  </li>
               </ul>
               <!-- END : Dashboard submenu list -->
           </li>
           <!-- END : Link with submenu -->

        </ul>
    </div>

    
 </div>
 <!-- End - Navigation menu -->

@include('includes.footer')
