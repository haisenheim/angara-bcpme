<template>
    <!-- Link with submenu -->
<li @click="toggle()"  class="nav-item">

<router-link data-bs-toggle="popover" data-bs-trigger="hover"
 @click="setActive()" :title="title" :data-bs-original-title="title" data-bs-placement="right" :to="{path:path}"  :class="collapsed?'collapsed':'',active" class="mininav-toggle nav-link"><i :class="icon" class="fs-2 me-2"></i>
    <span class="nav-label ms-1"><slot></slot></span>
</router-link>


</li>
<!-- END : Link with submenu -->
</template>

<script>
    export default{
        name:'SubmenuItem',
        props:{
            'sequence':Number,
            'name':String,
            'title':String,
            'path':String,
            'icon':String,
            'args':[],
        },
        data(){
            return{
                collapsed:true,
            }
        },
        mounted(){
            let popoverTriggerEl = document.querySelectorAll(
                '[data-bs-toggle="popover"]')[0];
                //console.log(popoverTriggerEl);
                //new bootstrap.Popover(popoverTriggerEl)
        },
        computed:{
            isActive(){
                return !this.collapsed;
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
        font-weight: 900;
    }
</style>
