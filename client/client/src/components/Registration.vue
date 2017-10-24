<template>
  <div class="registration ">
    <div class="row">
    <div  class="col-sm-4 col-md-4"></div>
    <div  class="col-sm-4 col-md-4">
      <div class="bg-red" id="err" style="width: 100%">{{err_reg}}</div>
        <div  class="form-group">
          <label for="name">First name</label>
          <input type="text" class="form-control" id="name"  placeholder="name" v-model.trim="name"  @blur="check_name">
        </div>
        <div  class="form-group">
          <label for="sname">Last name</label>
          <input type="text" class="form-control" id="sname" placeholder="last name" v-model.trim="surname"  @blur="check_name">
        </div>
        <div  class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email"  placeholder="email" v-model.trim="email" @blur="check_email">
        </div>
        <div  class="form-group">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" id="phone" placeholder="phone" v-model.trim="phone" @blur="check_phone">
        </div>
        <div  class="form-group">
          <label for="login">Login</label>
          <input type="text" class="form-control" id="login"  placeholder="login" v-model.trim="rlogin">
        </div>
        <div  class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" placeholder="password" v-model.trim="rpassword" >
        </div>
        <div class="center">
          <button class="btn btn-auth" @click="fregistration">registration</button> 
          <button class="btn btn-auth" @click="cancel" type="cancel">cancel</button>
        </div>
      </div>
    <div  class="col-sm-4 col-md-4"></div>  
  </div>
  </div>

</template>

<script>
import axios from 'axios'
export default {
  name: 'registr',
  data () {
    return {
     name: "",
      surname: "",
      rlogin: "",
      rpassword: "",
      email: "",
      phone: "",
      err_reg: "",
    }
  },
  methods:{

    cancel(){
      this.rlogin = this.rpassword = this.name = this.sname = this.phone = this.email = this.err_reg = ""
      this.$router.push({path:'/'})
  },




   fregistration(){
      if (this.check_phone()  && this.check_email() && this.check_name() && this.check_login()) {
			if(this.rlogin=="" || this.rpassword=="" || this.name=="" || this.surname=="" || this.phone=="" || this.email==""){
				this.err_reg = "not all fields are filled";
			}else{

        this.err_reg = ""    
        var self = this
        var data = new FormData()
        data.append("login", this.rlogin)
        data.append("password",this.rpassword)
        data.append("name",this.name)
        data.append("surname",this.surname)
        data.append("email",this.email)
        data.append("phone",this.phone)

        axios.post('http://localhost:88/BOOK_SHOP/client/api/auth/', data, this.config)         
        //axios.post('http://192.168.0.15/~user15/BOOK_SHOP/client/api/auth/', data, this.config)
          .then(function (response) {
            if(response.data.err && response.data.err ==true){
              self.err_reg = response.data['data']
              setTimeout(function () {
                self.err_reg = ""
              },2500);
            }else{
              self.$emit('client_reg',self.is_registr )
              self.err_reg = ""
              localStorage['hash_log'] = response.data['data']
              localStorage['user'] = self.rlogin
              self.$router.push({path:'/'})
            }
          })
          .catch(function (error) {
              self.err_reg = "sorry, there was a server error.Try again later"
          });
      }
      }
      setTimeout(function () {
         this.err_reg = ""
      },2500);  
   },

  
    check_phone(){
      var re = /^[\d]{10}$/;
      if (isNaN(this.phone) || re.test(this.phone) != true) {
        this.err_reg = "wrong phone format"
        return false
      }
      else {
        this.err_reg = ""
        return true
      }
	  },

    check_email() {
      var re = /^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?(\.)[A-Za-z0-9]{2,3}$/
      if (re.test(this.email) != true) {
        this.err_reg = "wrong email format"
        return false
      }
      else {
        this.err_reg = ""
        return true
      }
    },

    check_login(){
      var re = /^[\-_A-Za-z0-9]+$/
      if (re.test(this.rlogin) !== true){
        this.err_reg = 'login contains invalid characters'
        return false
      }
      else if (this.rlogin.length < 6){
        this.err_reg ='login is too short'
        return false
      }else {
        this.err_reg = ""
        return true
      }     
    },

    check_name(){
      var re1 = /^[A-Za-z]+$/
      if (re1.test(this.name) !== true){
          this.err_reg = 'name contains invalid characters'
          return false
      }else if(re1.test(this.surname) !== true){
        this.err_reg = 'surname contains invalid characters'
        return false
      }else {
        this.err_reg = ""
        return true
      }
    },

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.registration{
  position: relative;
  margin-top: 30px;
  color: #FFF
}

.center{
  text-align: center;
}

.bg-red{
  background: #F00;
}

#err{
  margin-bottom: 10px;
  line-height: 15px;
  text-align:center;
}

</style>
