<template>
<div class="editganre">
  <div class="err_info">{{errEdit}}</div>
  <div class="add_info">{{isEdit}}</div>
  <div class="add">
        <div class="lbl_add">Ganre(id:{{ganre_id}}) edit form:</div>
          <p class="bg-danger" id="err" style="width: 100%">{{err_ganre}}</p>
          <div class="form-group">
            <label for="inputGName">Name</label>
            <input type="text" class="form-control" id="inputGName" placeholder="Enter name" v-model="ganre.name">
          </div>
          <button  class="btn btn-primary" @click="edit_ganre">Edit ganre</button>
        </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'editGanre',
  data () {
    return {
      err_ganre: "",
      ganre: {},
      errEdit: "",
      isEdit: "",
      ganre_show: true
    }
  },
  created(){
  },
  methods:{
    edit_ganre(){
      var self = this
      if(this.ganre.name != ""){
      
      }else{
        this.errEdit = "fill the field"
        setTimeout(function () {
            this.errEdit = ""
        },2500);   
      }
   },
  },
  computed:{
    ganre_id: function(){
      var id = this.$route.params.id
      //alert(this.$route.params.id)
      var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/ganre/'+ this.$route.params.id, this.config)
        //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/ganre/'+ this.ganre_id, this.config)
            .then(function (response) {
              //console.log(response.data)
              self.ganre = response.data.data
               //console.log(temp)
              if( self.ganre.length == 0){
                self.errEdit = "Information about this gare is absent"
                self.ganre_show = false
              }
            })
            .catch(function (error) {
              self.errEdit = "Information about this ganre is absent"
              self.ganre_show = false

          }); 
      return id;
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
