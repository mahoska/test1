<template>
<div class="addauthor">
  <div class="err_info">{{errAdd}}</div>
  <div class="add_info">{{isAdd}}</div>
  <div class="add">
    <div class="lbl_add">Author add form</div>
      <p class="bg-danger" id="err" style="width: 100%">{{err_author}}</p>
      <div class="form-group">
        <label for="inputName">Name</label>
        <input type="text" class="form-control" id="inputName" placeholder="Enter name" v-model="name">
      </div>
      <div class="form-group">
        <label for="inputSname">Surname</label>
        <input type="text" class="form-control" id="inputSname" placeholder="Enter surname" v-model="surname">
      </div>
      <button  class="btn btn-primary"  @click="add_author">Add author</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'addAuthor',
  data () {
    return {
      err_author: "",
      name: "",
      surname: ""
    }
  },
  created(){
   
  },
  methods:{
   add_author(){
      var self = this
      if(this.name != "" || this.surname != ""){
      var data = new FormData()
      data.append("name", this.name) 
      data.append("surname", this.surname)  
        axios.post('http://localhost:88/BOOK_SHOP/client/api/author/', data, self.config)         
        //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/author/', data, self.config)
        .then(function (response) {
          if(response.data.err && response.data.err ==true){
              self.errAdd = response.data.data
              setTimeout(function () {
                self.errAdd = ""
              },2500);
            }else if(response.data.data == '1') {
            self.isAdd = "record successfully added"
            self.$emit('updateAuthors')
            setTimeout(function () {
              self.isAdd = ""
            },2500);   
          } 
          self.name = "" 
          self.surname = ""    
          })
          .catch(function (error) {
            self.errAdd = "error adding"
            setTimeout(function () {
                  self.errAdd = ""
            },2500);   
          });
      }else{
        this.errAdd = "error adding"
        setTimeout(function () {
            this.errAdd = "fill the fields"
        },2500);   
      }
   }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
