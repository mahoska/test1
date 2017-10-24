<template>
<div class="payment">
  <div class="row">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-7 col-md-7">
      <div class="order_err">{{isOrd}}</div>
      <div class="cart_info">{{isAddOrder}}</div>
      <div class="radio" v-for="pay in payment_methods" :key="pay.id" >
        <label>
          <input type="radio" name="payment"  :value="pay.id" v-model="payment_id">
          {{pay.name}}
        </label>
      </div>

      <router-link  :to="'/cart'" class="btn btn-def-light"  >
        Back
      </router-link>
      <button class="btn btn-def" @click="checkout">Checkout</button>
    </div>
    <div  class="col-sm-3 col-md-3"></div>
  </div>
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'bookItem',
  data () {
    return {
     payment_methods: [],
     payment_id: "",
     isOrd: "",
     isAddOrder: "",
     cart: [],
     total_sum_with_discount: 0,
     client: {},
     client_discount: 0,
     total_price: 0
    }
  },
  created(){
   
     var self = this
      axios.get('http://localhost:88/BOOK_SHOP/client/api/payment/', this.config)
      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/payment/', this.config)
            .then(function (response) {
              self.payment_methods = response.data.data 
      })
      .catch(function (error) {
      });
  },
  methods:{
    checkout(){
      this.isAddOrder = ""
      if(this.payment_id==""){
      this.isOrd = "You must select payment method"
       setTimeout(function () {
           this.isOrd = ""
      },2500);
      return;
      }
       var self = this
       this.isOrd = ""
        if(localStorage['hash_log'] && localStorage['user']){
        axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
        //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
          .then(function (response) {
            var client_id = parseInt(response.data.data)
            if(client_id>0){
              //-----get info about client  
              axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+ client_id, self.config)
              //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+ client_id , self.config)
                  .then(function (response) {                    
                    if(response.data.status == '200') {
                      self.client = response.data.data 
                      axios.get('http://localhost:88/BOOK_SHOP/client/api/cart/'+ client_id +'/1', self.config)
                      //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+ client_id +'/1', self.config)
                          .then(function (response) {
                            self.cart = response.data.data
                            if( self.cart.length == 0){
                                self.isOrd = "You cart is empty!"
                                setTimeout(function () {
                                    self.isOrd = ""
                                    return
                                },2500);
                            }
                            var total_sum_without_disc = 0
                            self.cart.forEach(function(element){
                                element.book_discount = parseFloat(element.book_discount)
                                element.price = parseFloat(element.price)
                                if(element.book_discount > 0 && element.book_discount < 100){
                                  element.new_price = element.price - element.price*element.book_discount/100
                                }else{
                                  element.new_price = element.price
                                }
                                element.sum = element.count*element.new_price
                                total_sum_without_disc  += element.sum
                            },self)
                            self.client_discount = parseFloat(self.client.discount)

                           if(self.client_discount > 0 && self.client_discount < 100){
                            self.total_sum_with_discount = total_sum_without_disc*(1-self.client_discount/100)
                          }else{
                            self.total_sum_with_discount = total_sum_without_disc
                          }
                          //alert(self.total_sum_with_discount)
                          var total_sum_with_discount_str = self.total_sum_with_discount.toFixed(2)
                          self.total_price = parseFloat(total_sum_with_discount_str)
                          //----create order
                          var data = new FormData()
                            data.append("client_id",client_id)
                            data.append("payment_id",self.payment_id) 
                            data.append("discount_client",self.client_discount)
                            data.append("total_price",self.total_price)
                            axios.post('http://localhost:88/BOOK_SHOP/client/api/order/', data, self.config)         
                            //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/order/', data, self.config)
                            .then(function (response) {
                              if(response.data.status=='200'){
                                var order_id = parseInt(response.data.data)
                                if(order_id>0) {
                                var count_ins = 0;   
                                   self.cart.forEach(function(element){

                                      var data = new FormData()
                                      data.append("order_id",order_id)
                                      data.append("book_id",element.id) 
                                      data.append("book_price",element.price)
                                      data.append("count",element.count)
                                      data.append("discount_book",element.book_discount)
                                      axios.post('http://localhost:88/BOOK_SHOP/client/api/fullinfoorder/', data, self.config)         
                                      //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/fullinfoorder/', data, self.config)
                                      .then(function (response) { 
                                          console.log(response.data)
                                          count_ins++
                                          if(count_ins == self.cart.length ){
                                            self.isAddOrder = "Thank you for your purchase. In the near future our manager will contact you."
                                            //axios.delete('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
                                            axios.delete('http://localhost:88/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
                                                .then(function (response) {
                                                  if(response.data.data='1'){
                                                    self.cart = []
                                                    self.$emit('clearCountTotal')
                                                  }
                                            })
                                            .catch(function (error) {
                                              console.log(error);
                                            }); 
                                          }
                                      }).catch(function (error) {
                                        console.log(error) 
                                      });
   
                                   }, self)
                                } else{
                                  self.isOrd = "Something wrong, the order was not issued. Try later"
                                  setTimeout(function () {
                                        self.isOrd = ""
                                      },2500);  
                                      return;  
                                }
                              }  
                          })
                          .catch(function (error) {
                            self.isOrd = "Something wrong, the order was not issued. Try later"
                            setTimeout(function () {
                                  self.isOrd = ""
                                },2500);  
                                return;   
                          });
                          //--end--create order
                          //--order_info--

                          //--end order_info--
                          }).catch(function (error) {
                           self.isOrd = "Something wrong, the order was not issued. Try later"
                            setTimeout(function () {
                              self.isOrd = ""
                            },2500);  
                          return; 
                        });
                    }//--if client ok

                }).catch(function (error) {
                  self.isOrd = "Something wrong, the order was not issued. Try later"
                    setTimeout(function () {
                      self.isOrd = ""
                    },2500);  
                  return; 
                });
        }else{
          self.isOrd = "for the purchase you must register"
          setTimeout(function () {
                self.isOrd = ""
              },2500);  
              return; 
        }
        })
        .catch(function (error) {
          self.isOrd = "for ordering you need to register"
          setTimeout(function () {
                self.isOrd = ""   
          },2500); 
          return;   
        });
    } else{
        this.isOrd = "for this action you need to register"
          setTimeout(function () {
            this.isOrd= ""
          },2500);
        this.$emit('setlogout')  
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.payment{
  margin: 50px 0px 50px 200px;
  text-align: left;
  color: #FFF;
}

.btn-def, .btn-def-light{
  width: 100%;
  color: white; 
  margin-top: 10px;
  width: 100px;
  background-color: #2C3E50;
  margin-right: 50px;
}

.btn-def-light{
  background-color: #4A838C;
}

.cart_info{
  color: white;
  margin-top: 50px;
  font-weight: bold;
  font-size: 17px;
}

.order_err{
  margin-right:200px;
  background-color: #F00;
  color:black;
  text-align: center;
}

</style>
