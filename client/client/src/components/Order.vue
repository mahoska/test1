<template>
<div class="order">
  
  <div class="row">
    <div  class="col-sm-2 col-md-2"></div>
    <div  class="col-sm-8 col-md-8">
      <div class="ord_err">{{isOrd}}</div>
      <table class="table table-bordered cart-table">
        <tr>
          <th>Date create</th>
          <th>Status</th>
          <th>Client discount</th>
          <th>Payment</th>
          <th>Total sum</th>
        </tr>
      </table>
      <div class="panel-group" id="accordion"  style="position:relative"  >
        <div class="panel panel-default " v-for="order in orders" :key="order.id">
          <table class="table table-bordered cart-table">
            <tr data-toggle="collapse" data-parent="#accordion" :href="'#'+order.id" @click="showOrder(order.id)">
                <td>{{order.date_order_create}}</td>
                <td>{{order.status}}</td>
                <td>{{order.discount_client}}</td>
                <td>{{order.payment}}</td>
                <td>{{order.total_price}}</td>
            </tr>
          </table>
          <div :id="order.id" class="panel-collapse collapse">
            <div class="panel-body">
              <div v-if="!orderItem.length">
                we do not have information on this order
              </div>
              <table class="simple"  v-if="orderItem.length">
                <tr>
                  <th>Book name</th>
                  <th>Count</th>
                  <th>Price</th>
                  <th>Discount</th>
                </tr>
                <tr v-for="record in orderItem" :key="record.book_id">
                  <td>{{record.name}}</td>
                  <td>{{record.count}}</td>
                  <td>{{record.book_price}}</td>
                  <td>{{record.discount_book}}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div  class="col-sm-2 col-md-2"></div>
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'order',
  data () {
    return {
      orders: [],
      isOrd: "",
      orderItem: [],
      isEmptyOrd: "",
    }
  },
  created(){
     var self = this
         if(localStorage['hash_log'] && localStorage['user']){
            axios.get('http://localhost:88/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
            //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/'+localStorage['hash_log']+"/"+localStorage['user'], this.config)
              .then(function (response) {
                var client_id = parseInt(response.data.data)
                if(client_id>0){ 
                    axios.get('http://localhost:88/BOOK_SHOP/client/api/order/'+ client_id, self.config)
                    //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/order/'+ client_id, self.config)
                          .then(function (response) {
                            self.orders = response.data.data 
                    })
                    .catch(function (error) {})
                } else{
                   self.isOrd = "for ordering you need to register or authorization"
                    setTimeout(function () {
                          self.isOrd = ""
                          self.$emit('setlogout') 
                    },2500);  
                }   
              }).catch(function (error) {
                self.isOrd = "for ordering you need to register or authorization"
                setTimeout(function () {
                      self.isOrd = ""   
                },2500); 
                return;   
              })
          } else{
            this.isOrd = "for this action you need to register or authorization"
              setTimeout(function () {
                this.isOrd= ""
              },2500);
            this.$emit('setlogout')  
          }
  },
  methods:{
    showOrder(order_id){
        var self = this
        axios.get('http://localhost:88/BOOK_SHOP/client/api/fullinfoorder/'+ order_id, this.config)
        //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/fullinfoorder/'+  order_id, this.config)
          .then(function (response) {
            self.orderItem = response.data.data
          })
          .catch(function (error) {
          })
        }
      }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.order{
  margin: 50px auto;
  min-height: 500px;
}
table {
			font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
      margin: 0px auto;
			font-size: 14px;
			background: white;
      color: black;
			max-width: 100%;
			width: 100%;
			border-collapse: collapse;
			text-align: left;
			/*background: url("../assets/blurry.jpg") no-repeat;*/
			/*background-position: 100% 55px;*/
		}
    th {
		  font-weight: normal;
		  color: #339;
		  padding: 10px 12px;
		}
		td {
		 background-image: url("/static/back.png");
		 filter:alpha(opacity=60);
		 opacity:0.6;
		 color:#000028;
		  border-top: 1px solid white;
		  padding: 10px 12px;
      width: 150px;
      cursor: pointer;
		}

    table.simple td  {
      cursor: default;
    }

  .ord_err{
    font-weight: bold;
    font-size: 17px;
    position: relative;
    color: #F00;
  }

</style>
