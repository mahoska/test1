<template>
<div class="bookItem">
  <div class="err_info">{{err}}</div>
  <div class="row" v-if="book_show">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-7 col-md-7">
      <h1 class="title">{{book.name}}</h1>
      <div class="authors">{{book.authors}}</div>
    </div>
    <div  class="col-sm-3 col-md-3"></div>
  </div>
  <div class="row">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-2 col-md-2">
          <img class='image_book style_border'  src='/static/book.png'  alt='picture' >
    </div>
    <div  class="col-sm-5 col-md-5">
       <div class="ganres">{{book.ganres}}</div>
      <div class="price">
        <div v-if="if_discount" class="old-price">old price: <s>{{book.price}} GRN</s></div>
        <div><span v-if="if_discount">NEW</span> PRICE: <span class="new-price"> {{new_price}} GRN </span></div>
      </div>
    </div>
    <div  class="col-sm-3 col-md-3"></div>
  </div>
  <div class="row">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-8 col-md-8 description">{{book.description}}</div>
    <div  class="col-sm-2 col-md-2"></div>
  </div>
   <div class="row order" v-if="is_login">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-8 col-md-8">
      <div class="cart_info">{{isAddCart}}</div>
      <div class="err_info_add">{{errAddCart}}</div>
      <div class='row'>
        <div  class="col-sm-2 col-md-2">
          <input  type='text'  class='order_count form-control' @keydown="checkNumber" v-model="book_count"/>
        </div>
        <div  class="col-sm-10 col-md-10">
          <button class='btn' @click='add_cart(book.id)'>Add to Cart</button>
        </div>
      </div>
    </div>
    <div  class="col-sm-2 col-md-2"></div>
  </div>
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'bookItem',
  props: ['log'],
  data () {
    return {
      book: {},
      if_discount: false,
      errAddCart: "",
      err: "",
      book_count: 1,
      isAddCart: "",
      bookId: "",
      book_show: true
    }
  },
  created(){
    var book_id = this.$route.params.book_id
    this.bookId = book_id
    var self = this
    axios.get('http://localhost:88/BOOK_SHOP/client/api/book/'+ this.bookId, this.config)
    //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/book/'+ this.bookId, this.config)
        .then(function (response) {
          self.book = response.data.data
          if(self.book.length == 0){
            self.err = "Information about this book is absent"
            self.book_show = false
          }
        })
        .catch(function (error) {
           self.err = "Information about this book is absent"
           self.book_show = false
      });
  },
  computed:{
    new_price: function(){
      if(parseInt(this.book.discount) > 0 ){
        this.if_discount = true
        return this.book.price - this.book.price * parseInt(this.book.discount) / 100
      }
      else{
        this.if_discount = false
        return this.book.price
      }
    },
    is_login: function(){
      if(this.log)
        return true
      else 
        return false
    }

  },
  methods:{
    checkNumber: function(event){
      // Allow: backspace, delete
      if (event.keyCode == 46 || event.keyCode == 8 ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
          //nothing to do
          return;
        }else{
          // Make sure it's a digit, and stop the keypress event
          if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
          }
        }
    },

    add_cart(book_id){
      var self = this
      if(localStorage['hash_log'] && localStorage['user']){
        axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
        //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
          .then(function (response) {
            var client_id = parseInt(response.data.data)
            if(client_id>0){
            var data = new FormData()
            data.append("book_id", book_id)
            data.append("client_id",client_id)
            data.append("count",self.book_count)
          
            axios.post('http://localhost:88/BOOK_SHOP/client/api/cart/', data, self.config)         
            //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/', data, self.config)
            .then(function (response) {
              if(response.data.data == '1') {
                self.isAddCart = "Book added to cart"
                self.$emit('changeCountTotal', self.book_count, true )
                self.book_count = 1
                setTimeout(function () {
                  self.isAddCart = ""
                },2500);   
              }     
          })
          .catch(function (error) {
            self.errAddCart = "Something wrong, the book was not added to the basket. Try later"
            setTimeout(function () {
                  self.errAddCart = ""
            },2500);  
            return;   
          });
        }else{
          self.errAddCart = "for the purchase you must register or authorization"
          setTimeout(function () {
                self.errAddCart = ""  
                self.$emit('setlogout')
              },2500);  
        }
        })
        .catch(function (error) {
          self.errAddCart = "for ordering you need to register or authorization"
          setTimeout(function () {
                self.errAddCart = ""
                self.$emit('setlogout') 
          },2500);   
        });
    }else{
      self.errAddCart = "for ordering you need to register or authorization"
        setTimeout(function () {
          self.errAddCart = ""
          this.$emit('setlogout')
      },2500); 
    }
    }
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.bookItem{
  text-align: left;
  color: #FFF;
}

.authors{
  font-weight: bold;
  font-size: 17px;
  margin-left: 20px;
}

.ganres{
  font-style: italic;
}

.price{
   color: red;
   font-weight: bold;
   margin-top: 40px;
}

.old-price{
  margin-bottom: 20px;
}

.new-price{
  font-size: 17px;
}

.description{
  font-size: 15px;
}

.title{
  font-family: Monotype Corsiva;
}

.err_info, .cart_info, .err_info_add{
  color:deepskyblue;
  margin-top: 20px;
  font-weight: bold;
  font-size: 17px;
}

.err_info_add{
  color: #F00;
}

.order_count{
  max-width: 100px;
}

.order{
  margin-top:20px;
}

.order button{
  background-color: #2C3E50;
  color: white;
}


</style>
