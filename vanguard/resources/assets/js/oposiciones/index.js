import Vue from 'vue'
import axios from 'axios'


new Vue({
    el: '#app',
    data: {
        marcas: [],
        form: {
            csrf_token: storage.csrf_token,
            pdf: ''
        }
    },
    methods: {
        fileChanged(event){
            this.form.pdf = ''
            var fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])

            fileReader.onload = (event) => {
                this.form.pdf = event.target.result
            }
        }
    }
})
