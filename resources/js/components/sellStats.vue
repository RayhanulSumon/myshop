<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mt-3 font-weight-bold text-center">Sell Stats</h4>
            </div>
        </div>
        <div class="row mt-3 pt-3" style="background-color: #eeeeee">

            <div class="col-md-6">
                <!-- Card group -->
                <div class="card-group">

                    <!-- Card -->
                    <div class="card mb-4">

                        <!-- Card content -->
                        <div class="card-body">

                            <!-- Title -->
                            <h6 class="card-title">Today Sell:</h6>
                            <!-- Text -->


                            <p class="card-text blue-text"><i class="fas fa-angle-double-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">{{ todaysell }}</span>taka</p>

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->
                    <!-- Card -->
                    <div class="card mb-4">

                        <!-- Card content -->
                        <div class="card-body">

                            <!-- Title -->
                            <h6 class="card-title">Monthly Sell:</h6>
                            <!-- Text -->


                            <p class="card-text blue-text"><i class="fas fa-angle-double-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">{{monthlysell}}</span>taka</p>

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->


                </div>
                <!-- Card group -->
            </div>
            <div class="col-md-6">
                <!-- Card group -->
                <div class="card-group">

                    <!-- Card -->
                    <div class="card mb-4" v-if="$gate.isAdmin()">

                        <!-- Card content -->
                        <div class="card-body" >

                            <!-- Title -->
                            <h6 class="card-title">Today Profit:</h6>
                            <!-- Text -->


                            <p class="card-text green-text"><i class="fas fa-angle-double-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">{{todayprofit}}</span>taka</p>

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->
                    <!-- Card -->
                    <div class="card mb-4" v-if="$gate.isAdmin()">

                        <!-- Card content -->
                        <div class="card-body">

                            <!-- Title -->
                            <h6 class="card-title">Monthly Profit:</h6>
                            <!-- Text -->


                            <p class="card-text green-text"><i class="fas fa-angle-double-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">{{monthlyprofit}}</span>taka</p>

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->


                </div>
                <!-- Card group -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "sellStats",
    data(){
        return {
            todaysell: {},
            monthlysell: {},
            todayprofit: {},
            monthlyprofit: {},
        }
    },
    methods:{
        loadstats(){
            // load user via axios
             axios.get('sell/stats').then(({data}) => {
                 this.todaysell = data.todaysell
                 this.monthlysell = data.monthlysell
                 this.todayprofit = data.todayprofit
                 this.monthlyprofit = data.monthlyprofit
             })
                 .catch(e => {
                 console.log(e);
             });
        },

    },
    created() {
        this.loadstats();
        Fire.$on('afterDelete', () => {
            this.loadstats();      // event listener
        });
         // run when component open
    },
}

</script>

<style scoped>

</style>
