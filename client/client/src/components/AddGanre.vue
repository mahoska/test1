<template>
<div class="addganre">
  <div class="err_info">{{errAdd}}</div>
  <div class="add_info">{{isAdd}}</div>
  <div class="add">
        <div class="lbl_add">Ganre add form</div>
          <p class="bg-danger" id="err" style="width: 100%">{{err_ganre}}</p>
          <div class="form-group">
            <label for="inputGName">Name</label>
            <input type="text" class="form-control" id="inputGName" placeholder="Enter name" v-model="ganre_name">
          </div>
          <button  class="btn btn-primary" @click="add_ganre">Add ganre</button>
        </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'addGanre',
  data () {
    return {
      err_ganre: "",
      ganre_name: "",
      errAdd: "",
      isAdd: ""
    }
  },
  created(){
   
  },
  methods:{
    add_ganre(){
      var self = this
      if(this.ganre_name != ""){
      var data = new FormData()
      data.append("name", this.ganre_name)  
        axios.post('http://localhost:88/BOOK_SHOP/client/api/ganre/', data, self.config)         
        //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/ganre/', data, self.config)
        .then(function (response) {
          console.log(response.data)
          if(response.data.err && response.data.err ==true){
              self.errAdd = response.data.data
              setTimeout(function () {
                self.errAdd = ""
              },2500);
            }else if(response.data.data == '1') {
            self.isAdd = "record successfully added"
            self.$emit('updateGanre')
            setTimeout(function () {
              self.isAdd = ""
            },2500);   
          } 
          self.ganre_name = ""    
          })
          .catch(function (error) {
            self.errAdd = "error adding"
            setTimeout(function () {
                  self.errAdd = ""
            },2500);   
          });
      }else{
        this.errAdd = "fill the field"
        setTimeout(function () {
            this.errAdd = ""
        },2500);   
      }
   }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
/*--error--*/
.err_info, .add_info{
  margin-top: 20px;
  font-weight: bold;
  font-size: 17px;
}

.err_info{
  color: #F00;
}
</style>
