<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <h3>Edit Profile</h3><br>
                <form @submit.prevent="updateProfile">
                    <div class="form-group" >
                        <label for="shop_name" class="form control">Shop Name:</label>
                        <input autocomplete="off" v-model="form.shop_name" type="text" name="shop_name" id="shop_name" placeholder="Shop Name"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('shop_name') }">
                        <has-error :form="form" field="shop_name"></has-error>
                    </div>
                    <div class="form-group" >
                        <label for="email" class="form control">Email:</label>
                        <input disabled autocomplete="off" v-model="form.email" type="text" name="shop_name" id="email" placeholder="Email"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <button type="submit"  class="btn btn-block btn-primary">SAVE</button>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            user: {},
            form: new Form({
                id: '',
                shop_name: '',
                email: '',
                password:  '',
                password_confirmation:  '',

            }),
        }

    },
    methods: {
        getUser(){
            // load user via axios
            this.$Progress.start();
            axios
                .get("getUser")
                .then(response => {
                    this.user = response.data;
                    this.form.fill(user);
                    this.$Progress.finish();
                })
                .catch(e => {
                    console.log(e);
                    this.$Progress.fail();
                });

        },

        updateProfile(){

            // load user via axios
            // axios.get('api/product').then(({data}) => (this.products = data.data));
            this.$Progress.start();
            axios
            this.form.post("user/update/profile/"+this.form.id)
                .then(response => {
                    this.getUser()
                    this.$Progress.finish();
                    // firing toast message
                    toast.fire({
                        background:  'rgba(43, 165, 137, 0.45) linear-gradient(#444,#111)',
                        icon: 'success',
                        title: '<span style="color:#FFF">Successfully</span>'
                    })
                })
                .catch(e => {
                    console.log(e);
                    this.$Progress.fail();
                    toast.fire({
                        background:  'rgba(43, 165, 137, 0.45) linear-gradient(#444,#111)',
                        icon: 'warning',
                        title: '<span style="color:#FFF">Failed !</span>'
                    })
                });

        },

    },
    created() {
        this.getUser();
    },
}
</script>

<style scoped>


</style>
