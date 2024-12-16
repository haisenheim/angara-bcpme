<template>
    <!-- Link with submenu -->
<li @click="toggle()" class="nav-item has-sub">

<a href="#" :class="collapsed?'collapsed':''" class="mininav-toggle nav-link"><i class="demo-pli-address-book fs-5 me-2"></i>
    <span class="nav-label ms-1">Species</span>
</a>

<!-- Dashboard submenu list -->
<ul :class="collapsed || !isParentCollapsed?'collapse':''" class="mininav-content nav">
    <li class="nav-item">
        <a href="./index.html" class="nav-link">Lion</a>
    </li>
    <li class="nav-item">
        <a href="./dashboard-2.html" class="nav-link">Snow</a>
    </li>
    <li class="nav-item">
        <a href="./dashboard-3.html" class="nav-link">Jaguar</a>
    </li>

</ul>
<!-- END : Dashboard submenu list -->

</li>
<!-- END : Link with submenu -->
</template>

<script>
    export default{
        name:'SubmenuItem',
        data(){
            return{
                collapsed:true,
            }
        },
        computed:{
            isActive(){
                return !this.collapsed;
            },
            isParentCollapsed(){
                return this.$store.state.navbar_min;
            },
            canCollapsed(){
                return (!this.$store.state.navbar_min && this.collapsed)
            }
        },
        methods:{
            toggle(){
                //document.querySelector("ul.mainnav__menu > li.nav-item > a.nav-link").classList.remove("active");
                if(this.$store.state.navbar_min){
                    this.collapsed=!this.collapsed;
                }

                //document.querySelector(".nav-item > a").classList.toggle("active");
            }
        }
    }

</script>
