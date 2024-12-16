<template>
    <!-- Link with submenu -->
<li @click="toggle()" class="nav-item has-sub">

<a href="#" :class="collapsed?'collapsed':'',active" class="mininav-toggle nav-link"><i :class="icon" class="fs-2 me-2"></i>
    <span class="nav-label ms-1"><slot></slot></span>
</a>

<!-- Dashboard submenu list -->
<ul :class="collapsed || !isParentCollapsed?'collapse':''" class="mininav-content nav">
    <li v-for="(link,index) in links" :key="index" class="nav-item">
        <router-link @click="setActive" :to="{ path:link.path }" class="nav-link">{{ link.label }}</router-link>
    </li>

</ul>
<!-- END : Dashboard submenu list -->

</li>
<!-- END : Link with submenu -->
</template>

<script>
    export default{
        name:'HasSubmenu',
        props:{
            'sequence':Number,
            'title':String,
            'icon':String,
            'links':[],
        },
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
            },
            active(){
                return this.$store.state.active == this.sequence?'active':''
            }
        },
        methods:{
            toggle(){
                if(this.$store.state.navbar_min){
                    this.collapsed=!this.collapsed;
                }
            },
            setActive(){
               this.$store.commit('SET_ACTIVE',this.sequence);
               console.log(this.$store.state.active);
            }
        }
    }

</script>

<style scoped>
    .nav-label{
        font-weight: 800;
    }
</style>
