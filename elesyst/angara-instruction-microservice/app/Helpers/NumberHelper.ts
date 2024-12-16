export default class NumberHelper{
  static convertTextToNumber(val){
    if(val.length==0){
      return 0
    }else{
      return Number.parseFloat(val)
    }
  }

  static sum(nums:any[]):number{
   return nums.reduce((car,item)=>{
      return car + this.convertTextToNumber(item)
    },0)
  }
}
