<template>
  <div id="app">
    <img class="bg" src="/static/logo1.jpg"/>
    <div>
    <nav class="navbar navbar-default navbar-fixed-top transp clearfix " role="navigation">
      <div class="container-fluid">
        <span class="span-logo">BOOK</span>
        <router-link  :to="'/'" class="book_name" >
          <img class="img-logo" src="/static/logo.png"/> 
        </router-link>
        <span class="span-logo">STORE</span>
      </div>
    </nav>
    <table class="mynav-table">
      <tr>
        <td>
          <form class="navbar-form navbar-right" role="search" >
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" v-model.trim="search_string">
              <button class="btn btn-default" @click="search">Search</button>
            </div>
          </form>
        </td>
      </tr>
      <tr>
        <td>
        <!--log form-->
          <div class="navbar-form navbar-right" v-if="is_not_login_check">
            <div class="err-log">{{err_log}}</div>
            <div  class="form-group">
              <input type="text" class="form-control" id="login"  placeholder="login" v-model.trim="login" >
            </div>
            <div  class="form-group">
              <input type="password" class="form-control" id="password" placeholder="password" v-model.trim="password" >
            </div>
            <button class="btn btn-auth" @click="flogin" >login</button>
            <router-link  :to="'/registration'"   class="btn btn-auth">
              Registration
            </router-link>
          </div>         
        <!--end log form-->
        <!--is_login_check form-->
        <div  class="auth-form" v-if="is_login_check">
          <table class="is_check">
            <tr>
              <td><span class="log_span">You login as:</span><br> {{login}}</td>
              <td>
                <button class="btn btn-auth" @click="logout" >
                  <span class="glyphicon glyphicon-log-out" aria-hidden="true" ></span>
                </button>
                <router-link  :to="'/cart'" class="btn btn-auth" >
                  <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" >{{bag_book_count}}</span>
                </router-link>
                <router-link  :to="'/order'" class="btn btn-auth" >
                  <span class="glyphicon glyphicon-folder-open" aria-hidden="true" ></span>
                </router-link>
              </td>
            </tr>
          </table>
        </div>
        <!--is_login_check form-->
        </td>
      </tr>
    </table>
  
    <div class="content">
      <router-link  :to="'/admin'"   class="btn btn-auth" style="position:relative">
             Admin
    </router-link>
      <router-view :filterstring="search_param"
      :log="is_login_check"  
      @setlogout="logout" 
      @clearSearch="clearSearchString"  
      @changeCountTotal="changeCart" 
      @clearCountTotal="clearTotal"
      @client_reg="registration_check" />
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'app',
  data () { 
    return{
      is_login_check: false,
      is_not_login_check: true,
      login: "",
      password: "",
      err_log: "",

      search_string: "",
      search_param: "",

      bag_book_count: 0
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
            self.is_login_check = true
            self.is_not_login_check = false
            self.login = localStorage['user']

            //--get client cart
            axios.get('http://localhost:88/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
            //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
              .then(function (response) {
                  if(response.data.data == null) 
                    self.bag_book_count = 0
                  else self.bag_book_count = parseInt(response.data.data)
              })
            } 
        })
    }
  },
  methods:{
    registration_check(client_login){
        this.is_login_check = true
        this.is_not_login_check = false
        this.login = client_login
    },

    flogin(){
      if(this.login=="" || this.password=="" ){
				this.err_log = "not all fields are filled";
			}else{
        var self = this
        this.err_log = ""
        //axios.put('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/', 
        axios.put('http://localhost:88/BOOK_SHOP/client/api/auth/', 
        {
          login: this.login,
          password: this.password
        }, this.config)
        .then(function (response) {
           if(response.data.err && response.data.err ==true){
              self.err_log = response.data.data
              setTimeout(function () {
                self.err_log = ""
              },2500);
            }else{
              self.is_login_check = true
              self.is_not_login_check = false
              localStorage['hash_log'] = response.data['data'].status
              localStorage['user'] = self.login
              var client_id = response.data['data'].client_id;
              //==get client cart
              axios.get('http://localhost:88/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
              //axios.get('http://192.168.0.15/~user15/BOOK_SHOP/client/api/cart/'+ client_id, self.config)
                .then(function (response) {
                  //console.log(response.data)
                  if(response.data.data == null) 
                    self.bag_book_count = 0
                  else self.bag_book_count = parseInt(response.data.data)
              })
              //location.reload();
            }
        })
        .catch(function (error) {
            self.err_log = "somthing wrong, authorization error"
            setTimeout(function () {
              self.err_log = ""
            },2500);
        });
      }
    },

    logout(){
      localStorage['hash_log'] = ""
      localStorage['user'] = ""
      localStorage['cart'] = ""
      this.is_login_check = false
      this.is_not_login_check = true
      this.login = ""
      this.password = ""
      this.$router.push({path:'/'})
    },

    search(){
      this.search_param =  this.search_string
      this.$router.push({path:'/'})
    },

    clearSearchString(){
      this.search_string = ""
      this.search_param = ""
    },

    changeCart(count, flag){
      if(flag)
        this.bag_book_count += parseInt(count)
      else
        this.bag_book_count -= parseInt(count)
    },

    clearTotal(){
      this.bag_book_count = 0
    }

  }
}
</script>

<style>
img.bg {
  /* Set rules to fill background */
  min-height: 100%;
  min-width: 1024px;
  /* Set up proportionate scaling */
  width: 100%;
  height: auto;
  /* Set up positioning */
  position: fixed;
  top: 0;
  left: 0;
}
@media screen and (max-width: 1024px) { /* Specific to this particular image */
  img.bg {
    left: 50%;
    margin-left: -512px;   /* 50% */
  }
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.navbar {
  background-color:#2c3037;
  opacity: 0.7;
  z-index: 9;
}
 
.content  {
  padding-top: 100px;
} 

.clearfix:after{
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.btn-auth{
  background-color: #2C3E50;
  color: #D8EEFA;
}

/*--logo nav--*/
.img-logo{
  max-height: 90px;
  margin-top: -40px;
}

.span-logo{
  font-size: 60px;
  font-weight: bold;
  line-height: 100px;
  color: #2C3E50;
}

.mynav:first-child{
  padding-left: 100px;
}
/*--end logo nav--*/

.log_span{
  font-weight: bold;
  color: #FFF;
  text-align:left;
  margin-left: -15px;
}

.mynav-table{
  position: fixed;
  opacity: 1;
  top: 0px;
  right: 10px;
  z-index: 999;
  text-align: right;
}

.is_check td{
  line-height:17px;
  text-align:center;
  width: 150px;
  color: #FFF;
}

.is_check{
  margin-top:5px;
  margin-left: 12px;
}

.err-log{
  font-size:11px;
  color:#F00;
  line-height:11px;
  text-align:left;
  margin-top: -5px;
}


#err{
  color: black;
}

.btn:hover{
  color:#FFF;
}

/*admin*/
.add{
  border: 1px solid lightgray;
  border-radius: 5px;
  padding:20px;
  margin-bottom:20px;
}

.lbl_add{
  text-align:right;
  font-weight:bold;
  text-decoration:underline;
}
</style>
