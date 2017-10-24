<template>
  <div class="general container">
    <div class="row">
      <div class="col-sm-12 col-md-3">
      <!--author-->  
      <div class="section">
        <router-link class="btn  btn-info"  :to="'/admin/addAuthor'">
          new Author
        </router-link>

        <div class="form-group">
          <label>Edit or Delete</label>
          <select v-model="author" class="form-control"  @change="changeSelectAuthor()">
            <option value="">select author</option>
            <option v-for="author in authors" :value="author.id" :key="author.id">
                  {{author.surname}} {{author.name}}
            </option>
          </select>
        </div>
      </div>
      <!--endauthor--> 
      <!--ganere-->
      <div class="section">
        <router-link class="btn  btn-info"  :to="'/admin/addGanre'">
          new Ganre
        </router-link>
        <div class="form-group">
          <label>Edit or Delete</label>
          <select v-model="ganre" class="form-control"  @change="changeSelectGanre()">
            <option value="">select ganre</option>
            <option v-for="ganre in ganres" :value="ganre.id" :key="ganre.id">
              {{ganre.name}}
            </option>
          </select>
        </div>
      </div>
      <!--endganre-->
      <!--book-->
      <div class="section">
        <router-link class="btn  btn-info"  :to="'/admin/addBook'">
          new Book
        </router-link>

        <div class="form-group">
          <label>Edit or Delete</label>
          <select v-model="book" class="form-control"  @change="changeSelectBook()">
            <option value="">select book</option>
            <option v-for="book in books" :value="book.id" :key="book.id">
              {{book.name}}
            </option>
          </select>
        </div>
      </div>
      <!--endbook-->
      </div>

      <div class="col-sm-12 col-md-9">
           <router-view 
           @updateGanre="getGanres"
           @updateAuthors="getAuthors"
           ></router-view>
       </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'general_admin',
  data () {
    return {
       config: {
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      },
      err_author: "",
      name: "",
      surname: "",
      is_author: false,
      author: "",
      authors: [],

      err_ganre: "",
      ganre_name: "",
      is_ganre: false,
      ganres: [],
      ganre: "",


      err_book: "",
      is_book: false,
      books: [],
      book: "",

     

    }
  },
  created(){
      this.getGanres()
      this.getAuthors()
      this.getAuthors()
  },
  methods:{
    getGanres(){
      var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/ganre/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/ganre/', this.config)
            .then(function (response) {
              console.log(response.data);
                self.ganres = response.data.data
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getAuthors(){
      var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/author/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/author/', this.config)
            .then(function (response) {
              console.log(response.data);
                self.authors = response.data.data
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getBooks(){
      var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/book/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/book/', this.config)
            .then(function (response) {
              console.log(response.data);
                self.books = response.data.data
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    changeSelectAuthor(){
      
    },

    changeSelectGanre(){
      this.$router.push({path: '/admin/editGanre/'+ this.ganre})
    },
  
    changeSelectBook(){

    }

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.general{
  text-align: left;
  color: #FFF;
}

.section{
  margin-bottom: 50px;
}



</style>
