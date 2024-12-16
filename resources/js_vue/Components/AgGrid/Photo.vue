<template>
    <span class="imgSpanLogo">
        <Image pt:image:class="img-circle rounded-circle logo" :src="cellValueLowerCase" alt="Image" width="50" preview />
      </span>
</template>

<script>
    import Image from 'primevue/image';
    export default{
        name:"PhotoComponent",
        components:{
            Image,
        },
        data() {
            return {
            cellValue: '',
            cellValueLowerCase: ''
            };
      },
      beforeMount(){
        this.cellValue = this.params.value;
        this.cellValueLowerCase = this.params.value.toLowerCase();
      },
    }

</script>

<style scoped>
    .logo {
    display: block;
    width: 50px;
    height: 50px;
    margin-right: 12px;
    filter: brightness(1.1);
}

.imgSpan {
    display: flex;
    height: 100%;
    width: 100%;
    align-items: center;
}

.imgSpanLogo {
    display: flex;
    height: 100%;
    width: 100%;
    align-items: center;
    justify-content: center;
}

.missionSpan {
    display: flex;
    justify-content: center;
    height: 100%;
    align-items: center;
}

.missionIcon {
    width: auto;
    height: auto;
}

.priceIcon {
    display: block;
    width: 15px;
    height: auto;
    max-height: 50%;
    margin-right: 2px;
}

.logoCell {
    background-color: #fff3;
}
</style>
