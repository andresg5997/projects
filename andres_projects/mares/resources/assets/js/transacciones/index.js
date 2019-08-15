import Vue from 'vue'
import axios from 'axios'
import toastr from 'toastr'
import moment from 'moment'


const app = new Vue({
    el: '#app',
    data: {
        transacciones: []
    },
    created(){
        this.getData()
    },
    methods: {
        getData(){
            axios.get(laroute.route('api.transacciones'))
                .then((res) => {
                    this.transacciones = res.data.transacciones
                })
        }
    },
    filters: {
        filter(date){
            return moment(date).format('DD-MM-YYYY')
        },
        humanize(date){
            return moment(date).locale('es').fromNow()
        }
    }
})
