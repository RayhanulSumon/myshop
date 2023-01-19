<template>
    <div class="container" >
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{outstock}} Products</h4>
                        <div class="card-tools">

                            <button  class="btn btn-primary btn-sm mr-0 " @click="loadProducts">All </button>
                            <button  class="btn btn-warning btn-sm mr-4 " @click="outOfStock">Out Of Stock </button>
                            <button v-if="$gate.isAdmin()"  class="btn btn-success btn-sm" @click="createModal">ADD <i class="fas fa-plus-circle"></i></button>

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
                        <div style="margin: auto;text-align: center" v-if="products.length === 0" class="alert alert-warning mt-5" role="alert">
                            <h5><strong >Opps ! There is no Product</strong ></h5>
                        </div>
                        <div class="table-responsive" v-if="products.length !== 0" >
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th style="width:2%">#</th>
                                    <th>Name</th>
                                    <th style="width:10%">Qty</th>
                                    <th>Price</th>
                                    <th>Sell</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(product, index) in products" :key="product.id">
                                    <td>{{index+1}}</td>
                                    <td>{{product.name}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>{{product.stock_price}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button  class="btn btn-sm btn-success mr-5" @click="openModal(product)"><i class="fas fa-plus-circle"> SELL</i></button>

                                            <button v-if="$gate.isAdmin()"  class="btn btn-sm btn-primary mr-1" @click="editModal(product)"><i class="fa fa-edit blue"></i></button>

                                            <a v-if="$gate.isAdmin()" href="#" @click="deleteProduct(product.id)" class="btn btn-danger btn-sm">
                                                 <i class="fa fa-trash red"></i></a>
                                        </div>
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
                        <h5 class="modal-title" v-show="isCreate" id="createNewTitle">Create Product</h5>
                        <h5 class="modal-title" v-show="isEdit" id="createNewTitle2">Edit Product</h5>
                        <h5 class="modal-title" v-show="isSell" id="createNewTitle3">{{form.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- <form @submit.prevent="isEdit ? updateProduct() : createProduct()"> -->
                    <form @submit.prevent="isEdit ? updateProduct() :isSell ? createSell() : createProduct()">
                        <div class="modal-body">
                            <div v-if="!isSell">
                                <div class="form-group" >
                                    <label for="name" class="form control">Name:</label>
                                    <input autocomplete="off" v-model="form.name" type="text" name="name" id="name" placeholder="Product Name"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group" >
                                    <label for="qty" class="form control">Quantity:</label>
                                    <input autocomplete="off" v-model="form.quantity" type="number" id="qty" name="quantity" placeholder="quantity"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }">
                                    <has-error :form="form" field="quantity"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="stprice" class="form control">Stock Price:</label>
                                    <input autocomplete="off" v-model="form.stock_price" type="number" id="stprice" name="price" placeholder="stock price"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('stock_price') }">
                                    <has-error :form="form" field="stock_price"></has-error>
                                </div>
                            </div>
                            <div v-if="isSell">
                                <div class="form-group">
                                    <label for="qty" class="form control">Quantity:</label>
                                    <input autocomplete="off" v-model="form.qty" type="number" id="q" name="quantity" placeholder="quantity"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('qty') }">
                                    <has-error :form="form" field="q"></has-error>
                                    <small class="text-muted">stock: {{form.quantity}} item</small>
                                </div>

                                <div class="form-group">
                                    <label for="sprice" class="form control">Total Sell Price:</label>
                                    <input autocomplete="off" v-model="form.sell_price" type="number" id="sprice" name="sell_price" placeholder="Total Sell price"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('sell_price') }">
                                    <has-error :form="form" field="sell_price"></has-error>
                                    <small class="text-muted">stock: {{form.stock_price}} Taka per Qty</small>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close <i class="fas fa-times-circle"></i></button>
                            <button type="submit" v-if="isCreate" class="btn btn-primary">Create  <i class="fas fa-plus-square"></i></button>
                            <button type="submit" v-if="isEdit" class="btn btn-primary">Update </button>
                            <button type="submit" v-if="isSell" class="btn btn-primary">Sell </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {

    data(){
        return{
            query: "",
            queryFiled: "name",
            isCreate: false,
            isEdit: false,
            isSell: false,
            products : {}, // store loadUser data
            categories : {}, // store loadUser data
            outstock: "",
            form: new Form({
                id: '',
                name: '',
                quantity: '',
                stock_price: '',
                sell_price: '',
                qty: '',
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
                    "search/product/" +
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
        editModal(product){
            this.isCreate = false;
            this.isSell = false;
            this.isEdit = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(product);
            $('#createNew').modal('show')
        },
        createModal(){
            this.isSell = false;
            this.isEdit = false;
            this.isCreate = true;
            this.form.reset();
            $('#createNew').modal('show')
        },
        openModal(product){
            this.isSell = true;
            this.isEdit = false;
            this.isCreate = false;
            this.form.reset();
            this.form.clear();
            this.form.fill(product);
            this.form.qty ='';
            this.form.sell_price ='';
            $('#createNew').modal('show')
        },
        updateProduct(){
            this.$Progress.start()
            // create user method
            // sent ajax post request to API\UserController@store
            this.form.put('product/'+this.form.id).then(() =>{
                this.$Progress.finish();
                Fire.$emit('afterCreate') ;         // create event
                $('#createNew').modal('hide')  ;   // hide model
                // firing toast message
                toast.fire({
                    background:  'rgba(43, 165, 137, 0.45) linear-gradient(#444,#111)',
                    icon: 'success',
                    title: '<span style="color:#FFF">Product Update successfully</span>'
                })
            }).catch(() =>{
                this.$Progress.fail();
                toast.fire({
                    type: 'warning',
                    title: 'Failed'
                })
            })

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
        createProduct(){
            this.$Progress.start();
            this.form.post('product?page=' + this.pagination.current_page, {
                // Transform form data to FormData
                transformRequest: [function (data, headers) {
                    return objectToFormData(data)
                }],
                onUploadProgress:e => {
                    // Do whatever you want with the progress event
                    // console.log(e)
                }
            }).then(() =>{
                this.$Progress.finish();
                Fire.$emit('afterCreate');          // create event
                $('#createNew').modal('hide')  ;   // hide model
                toast.fire({
                    background:  'rgba(43, 165, 137, 0.45) linear-gradient(#444,#111)',
                    icon: 'success',
                    title: '<span style="color:#FFF">Product Create successfully</span>'
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
                    this.form.delete('product/'+id).then(() => {
                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        this.$Progress.finish();
                        Fire.$emit('afterCreate')    // create event
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

            this.outstock = '';

            // load user via axios
            // axios.get('api/product').then(({data}) => (this.products = data.data));
            this.$Progress.start();
            axios
                .get("product?page=" + this.pagination.current_page)
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
        outOfStock(){
            this.outstock = 'Out of Stock';
            this.$Progress.start();
            axios
                .get("outstock?page=" + this.pagination.current_page)
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
        // if (this.$gate.isAdmin()){
        // }
        this.loadProducts();

        Fire.$on('afterCreate', () => {
            this.loadProducts();       // event listener
        });
    },

}
</script>
