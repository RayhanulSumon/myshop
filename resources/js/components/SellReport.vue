<template>
    <div class="container">
        <div class="row" >
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{whichdate}} Sell Reports</h4>
                        <div class="card-tools" >

                            <button  class="btn btn-primary btn-sm mr-0 " @click="getSell('today')">Today</button>
                            <button  class="btn btn-success btn-sm mr-0 " @click="getSell('monthly')">Monthly</button>
                            <button  class="btn btn-dark btn-sm mr-0 " @click="getSell('all')">All </button>


                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Search:</strong>
                                </div>
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-7">
                                    <input  v-model="query" autocomplete="off"  type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>
                        <div style="margin: auto;text-align: center" v-if="products.length == 0" class="alert alert-warning mt-5" role="alert">
                            <h5><strong >Opps ! There is no Sell</strong ></h5>
                        </div>
                        <div class="table-responsive"v-if="products.length !== 0" >
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                    <th>Date</th>
                                    <th v-if="$gate.isAdmin()">Profit</th>
                                    <th v-if="$gate.isAdmin()">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(product, index) in products" :key="product.id">
                                    <td>{{index+1}}</td>
                                    <td>{{product.name}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>{{product.sell_price}}</td>
                                    <td>{{product.created_at}}</td>
                                    <td v-if="$gate.isAdmin()">{{product.profit}}</td>
                                    <td v-if="$gate.isAdmin()">
                                      <a href="#"  @click="deleteProduct(product.id)" class="btn btn-danger btn-sm">
                                         <i class="fa fa-trash red"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination
                            v-if="pagination.last_page > 1"
                            :pagination="pagination"
                            :offset="5"
                            @paginate="query === '' ? loadProducts() : searchData()"
                        ></pagination>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" data-backdrop="static" id="createNew" tabindex="-1" role="dialog" aria-labelledby="createNew" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="createNewTitle">{{form.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- <form @submit.prevent="isEdit ? updateProduct() : createProduct()"> -->
                    <form @submit.prevent="createSell()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="qty" class="form control">Quantity:</label>
                                <input v-model="form.qty" type="text" id="qty" name="quantity" placeholder="quantity"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('qty') }">
                                <has-error :form="form" field="qty"></has-error>
                                <small class="text-muted">stock: {{form.quantity}} item</small>
                            </div>

                            <div class="form-group">
                                <label for="sprice" class="form control">Sell Price:</label>
                                <input v-model="form.sell_price" type="text" id="sprice" name="sell_price" placeholder="Sell price"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('sell_price') }">
                                <has-error :form="form" field="sell_price"></has-error>
                                <small class="text-muted">stock: {{form.stock_price}} Taka</small>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close <i class="fas fa-times-circle"></i></button>
                            <button type="submit"  class="btn btn-primary">SUBMIT  <i class="fas fa-plus-square"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         <SellStats></SellStats>

    </div>
</template>

<script>
import SellStats from './sellStats'

export default {
    components: {
        SellStats
    },
    data(){
        return{
            query: "",
            queryFiled: "name",
            products : {}, // store loadUser data
            categories : {}, // store loadUser data
            whichdate: "Today",
            // two way form binding using vform object
            form: new Form({
                id: '',
                name: '',
                quantity: '',
                qty: '',
                stock_price: '',
                sell_price: '',
            }),

            pagination: {
                current_page: 1
            }
        }
    },
    watch: {

        query: function(newQ,old) {
            if (newQ === "") {
                this.loadProducts();
            } else {
                this.searchData();
            }
        }
    },
    methods:{

        searchData() {
            this.$Progress.start();
            axios
                .get(
                    "search/sell/" +
                    this.queryFiled +
                    "/" +
                    this.query +
                    "?page=" +
                    this.pagination.current_page
                )
                .then(response => {
                    this.products = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(e => {
                    console.log(e);
                    this.$Progress.fail();
                });
        },
        openModal(product){
            this.isEdit = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(product);
            this.form.qty ='';
            this.form.sell_price ='';
            $('#createNew').modal('show')
        },

        createSell(){
            this.$Progress.start()
            // create user method
            // sent ajax post request to API\UserController@store
            this.form.post('sell/store').then(() =>{
                this.$Progress.finish();
                Fire.$emit('afterCreate') ;         // create event
                $('#createNew').modal('hide')  ;   // hide model
                // firing toast message
                toast.fire({
                    background:  'rgba(43, 165, 137, 0.45) linear-gradient(#444,#111)',
                    icon: 'success',
                    title: '<span style="color:#FFF">Sell Done</span>'
                })
            }).catch(() =>{
                this.$Progress.fail();
                toast.fire({
                    type: 'warning',
                    title: 'Failed'
                })
            })

        },
        deleteProduct(id){
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start()
                    this.form.delete('sell/'+id).then(() => {
                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        this.$Progress.finish();
                        Fire.$emit('afterCreate')    // create event
                        Fire.$emit('afterDelete')    // create event
                    }).catch(() => {
                        this.$Progress.fail();
                        swal.fire(
                            'Failed!',
                            'There was something wrong',
                            'warning'
                        )
                    })
                }

            })

        },

        loadProducts(){

            // load user via axios
            // axios.get('api/product').then(({data}) => (this.products = data.data));
            this.$Progress.start();
            axios
                .post("sell/report?page=" + this.pagination.current_page)
                .then(response => {
                    this.products = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(e => {
                    console.log(e);
                    this.$Progress.fail();
                });

        },

        getSell($para){
            if ($para==='today'){
                this.whichdate = 'Today'
            }else if($para==='monthly'){
                this.whichdate = 'Monthly'
            }else if($para==='all'){
                this.whichdate = 'All'
            }
            this.$Progress.start();
            axios
                .post("sell/report?page=" + this.pagination.current_page,{
                    para: $para
                })
                .then(response => {
                    this.products = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(e => {
                    console.log(e);
                    this.$Progress.fail();
                });

        },

    },

    created() {

        this.loadProducts(); // run when component open
        Fire.$on('afterCreate', () => {
            this.loadProducts();       // event listener
        });
    },

}
</script>
