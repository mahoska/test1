<template>
  <div class="general">
    <div class="row">
      <div  class="col-sm-3 col-md-3">
        <div class="list-group">
          <button class="btn filter-active btn-def"  @click="showAll">Show All Book</button>
        </div>

        <div class="list-group">
          <div class="list-group-item active filter-active">
            Ganres
          </div>
          <div  class="list-group-item filter-item" v-for="ganre in ganres" :key="ganre.id" @click="selectByGanre(ganre.id)">{{ganre.name}}</div>
        </div>

        <div class="list-group">
          <div class="list-group-item active filter-active">
            Authors
          </div>
          <div  class="list-group-item filter-item" v-for="author in authors" :key="author.id" @click="selectByAuthor(author.id)">{{author.name}} {{author.surname}}</div>
        </div>

        </div>
        <div  class="col-sm-9 col-md-9 book-list" >
        <!--booklist-->
        <div v-if="show_book">
          <div class="filter_info">{{filter_info}}</div>
          <div class="search_info" v-if="is_search">
            <p>Search for a match in the title of the book: </p>
            <p class="search_info_res">{{search_info_res}}</p>
          </div>

          <div class="book_section" v-if="is_book_show">
              <book-section v-for="book in books" :book="book" :key="book.id" ></book-section>
          </div>
        </div>
        <!-- end booklist-->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import BookMin from './BookMiniature.vue'
//import BookItem from './BookItem.vue'
export default {
  name: 'general',
  props: ['filterstring'],
  data () {
    return {
      ganres: {},
      authors: {},
      books: [],
      is_filter: false,
      is_book_show: true,
      filter_info: "",
      ganres_book: {},
      author_book: {},
      search_info_res: "",
      show_book : true,


      //show_book_item: false,
      //show_book_item_id: 0,
      
    }
  },
  components: {
		'book-section': BookMin,
    //'book-item': BookItem
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

      this.filter()
  },
  methods:{
    selectByGanre(ganre_id){
      this.filter(ganre_id,"")
    },

    selectByAuthor(author_id){
      this.filter("", author_id)
    },

    showAll(){
      this.filter()
    },

    filter(ganre_id = "", author_id = ""){
      var self = this
      //this.show_book = true
      //this.show_book_item = false
      this.filter_info = ""
      this.search_info_res = ""
      var req_str = ""
      self.$emit('clearSearch')
      if((""+ganre_id) != "") req_str = ganre_id + "/-"
      else if((""+author_id) != "") req_str = "-/"+author_id
      else req_str = ""
      axios.get('http://localhost:88/BOOK_SHOP/client/api/book/' + req_str, this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/book/' + req_str, this.config)
            .then(function (response) {
              console.log(response)
              self.books = response.data.data
              if(self.books.length == 0)
                self.filter_info = "books on the your specified parameter is not found"
      })
      .catch(function (error) {
        console.log(error)
      });
    },

    search(){
      var self = this
      this.show_book = true
      //this.show_book_item = false
      this.filter_info = ""
      this.search_info_res = ""
      axios.get('http://localhost:88/BOOK_SHOP/client/api/book/' + this.filterstring, this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/book/' + this.filterstring, this.config)
            .then(function (response) {
              self.books = response.data.data
              if(self.books.length == 0)
                self.search_info_res = " no results "
      })
      .catch(function (error) {
        console.log(error)
      });

    },

    /*showBookItem(book_item_id){
      this.show_book_item_id = book_item_id
      this.show_book_item = true
      this.show_book = false
      //this.$nextTick(function () {
        //console.log(this.show_book_item_id) 
      //})
    },*/

    /*changeOrder(count){
      //alert(count)
      this.$emit('changeCountTotal', count )
    }*/
  },
  computed:{
    is_search: function(){ 
      var self = this
     
      if(this.filterstring != "") {
        this.search()
        return true
      }else {
        return false
      }
    }

   }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.general{
  margin: 0 auto;
  width: 1180px;
}

.book_section{
  position: relative;
  z-index: 1;
}

div .filter-active{
  background-color: #4A838C;
  border: none;
}

div .filter-item{
  font-weight: bold;
  cursor: pointer;
}

.btn-def{
  width: 100%;
  color: white; 
  margin-top: 10px;
}

.book-list{
   margin-top: 10px;
}

.filter_info, .search_info{
  color: white;
  margin-top: 50px;
  font-weight: bold;
  font-size: 17px;
}

.search_info{
  text-align: left;
  margin-bottom: 20px;
}

.search_info_res{
  text-align: center;
}
</style>
