<template>
<div class="addbook">
  <div class="add">
    <div class="lbl_add">Book add form</div>
      <p class="bg-danger" id="err" style="width: 100%">{{err_book}}</p>

      <div class="form-group">
        <label for="inputName">Name</label>
        <input type="text" class="form-control" id="inputName" placeholder="Enter name" v-model="name">
      </div>

      <div class="form-group">
        <label for="inputDescr">Description</label>
        <textarea class="form-control" id="inputDescr" placeholder="Enter description" v-model="description" rows="5"></textarea>
      </div>

      <div class="row">
        <div  class="col-sm-10 col-md-5">
          <div class="form-group">
            <label for="inputPrice">Price</label>
            <input type="number" min='0' step='0.1' class="form-control" id="inputPrice" placeholder="Enter price" v-model="price">
          </div>
        </div>
        <div  class="col-sm-2 col-md-2"></div>
        <div  class="col-sm-10 col-md-5">
          <div class="form-group">
            <label for="inputDiscount">Discount</label>
            <input type="number" min='0' step='0.1' class="form-control" id="inputDiscount" placeholder="Enter discount" v-model="discount">
          </div>
        </div>
      </div>    

      <div class="row">
        <div  class="col-sm-10 col-md-5">
          <div class="form-group">
            <label for="inputGanre">Ganre(s)</label>
            <select multiple class="form-control" v-model="book_ganres">
              <option v-for="ganre in ganres" :key="ganre.id" :v-model="ganre.id">{{ganre.name}}</option>
            </select>
          </div>
        </div>
        <div  class="col-sm-2 col-md-2"></div>
        <div  class="col-sm-10 col-md-5">
          <div class="form-group">
            <label for="inputAuthor">Author(s)</label>
            <select multiple class="form-control"  v-model="book_authors">
              <option v-for="author in authors" :key="author.id" :v-model="author.id">{{author.name}} {{author.surname}}</option>
            </select>
          </div>
        </div>
      </div>
      
      <button  class="btn btn-primary">Add book</button>
      <input type="reset" class="btn btn-def" value="Cancel">
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'addAuthor',
  data () {
    return {
      err_book: "",
      name: "",
      price: 0,
      description: "",
      discount: 0,
      ganres: [],
      authors: [],
      book_ganres: [],
      book_authors: [],

    }
  },
  created(){
    var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/ganre/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/ganre/', this.config)
            .then(function (response) {
              self.ganres = response.data.data 
              //console.log(self.ganres)
      })
      .catch(function (error) {
        //console.log(error)
      });

      axios.get('http://localhost:88/BOOK_SHOP/client/api/author/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/author/', this.config)
            .then(function (response) {
              //console.log(response.data)
              self.authors = response.data.data 

      })
      .catch(function (error) {
        console.log(error)
      });
  },
  methods:{
   
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
