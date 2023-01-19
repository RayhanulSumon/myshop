<template>
    <div>
        <div v-if="showLogin" class="login-form">
            <form @submit.prevent="loginSubmit">
                <div class="avatar">
                    <img class="material-icons" height="100" width="100"   v-bind:src="('icon1.png')" alt="">
                </div>
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input v-model="form.email" type="email" name="email" placeholder="Email"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.password" type="password" name="password" placeholder="Password"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
                <div class="form-group small clearfix">
                    <label class="checkbox-inline"><input type="checkbox" v-model="form.remember"> Remember me</label>
                    <!--                <a href="#" class="forgot-link">Forgot Password?</a>-->
                </div>
                <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">

            </form>
            <div class="text-center small ctext">Don't have an account? <a @click.prevent="showLogin = false" href="#">Sign up</a></div>
        </div>

        <div v-if="!showLogin" class="login-form">
            <form @submit.prevent="signupSubmit">
                <div class="avatar">
                    <img class="material-icons" height="100" width="100"   v-bind:src="('icon1.png')" alt="">
                </div>
                <h4 class="modal-title">Create New Account</h4>
                <div class="form-group">
                    <input v-model="form.shop_name" type="text" autocomplete="off" name="shop_name" placeholder="Your Shop Name"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('shop_name') }">
                    <has-error :form="form" field="shop_name"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.email" type="email" name="email" placeholder="Email"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.password" type="password" name="password" placeholder="Password"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.password_confirmation" type="password" name="password_confirmation" placeholder="confirm password"
                           class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                    <has-error :form="form" field="password_confirmation"></has-error>
                </div>

                <input type="submit" class="btn btn-primary btn-block btn-lg" value="Register">

            </form>
            <div class="text-center small ctext">Have an account? <a @click.prevent="showLogin = true" href="#">Login</a></div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            showLogin: true,
            form: new Form({
                shop_name: '',
                email: '',
                password:  '',
                password_confirmation:  '',
                remember: '',

            }),
        }

    },
    methods:{
        loginSubmit (){
            this.$Progress.start();
            this.form.post('admin/login/submit').then((response) =>{


                if (response.data === 'success'){
                    this.$Progress.finish();
                    toast.fire({
                        type: 'success',
                        title: 'Login Successfull'
                    })
                    //this.$router.push({ path: '/dashboard' })
                    window.location.replace(window.location.origin+"/products");

                }else{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'warning !',
                        title: 'Login Failed'
                    })
                }

            })

                .catch(() =>{

                })


        },
        signupSubmit (){
            this.$Progress.start();
            this.form.post('admin/register/submit').then((response) =>{


                if (response.data === 'success'){
                    this.$Progress.finish();
                    toast.fire({
                        type: 'success',
                        title: 'Login Successfull'
                    })
                    //this.$router.push({ path: '/dashboard' })
                    window.location.replace(window.location.origin+"/products");

                }else{
                    this.$Progress.fail();
                    toast.fire({
                        type: 'warning !',
                        title: 'Login Failed'
                    })
                }

            })

                .catch(() =>{

                })


        },
    },
}
</script>

<style scoped>

.login-form {
    width: 350px;
    margin: 0 auto;
    padding: 30px 0;
}
.login-form form {
    color: #434343;
    border-radius: 1px;
    margin-bottom: 15px;
    background: #fff;
    border: 1px solid #f3f3f3;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
    padding: 30px;
}
.login-form h4 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 20px;
}
.login-form .avatar {
    color: #fff;
    margin: 0 auto 30px;
    text-align: center;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    z-index: 9;
    /*background: #4aba70;*/
    /*padding: 15px;*/
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
}

.login-form .avatar i {
    font-size: 62px;
}
.login-form .form-group {
    margin-bottom: 20px;
}
.login-form .form-control, .login-form .btn {
    min-height: 40px;
    border-radius: 2px;
    transition: all 0.5s;
}
.login-form .close {
    position: absolute;
    top: 15px;
    right: 15px;
}
.login-form .btn {
    background: #4aba70;
    border: none;
    line-height: normal;
}
.login-form .btn:hover, .login-form .btn:focus {
    background: #42ae68;
}
.login-form .checkbox-inline {
    float: left;
}
.login-form input[type="checkbox"] {
    margin-top: 2px;
}
.login-form .forgot-link {
    float: right;
}
.login-form .small {
    font-size: 15px;
}
.login-form a {
    color: #ba4a4a;
}
.ctext{
    font-size: 14px;
}
</style>
