import Vue from 'vue'
import axios from 'axios'
import { VueMaskDirective } from 'v-mask'

Vue.directive('mask', VueMaskDirective);

const app = new Vue({
    el: '#app',
    data: {
        form: {
            csrf_token: storage.csrf_token,
            name: '',
            country: 'US',
            sport_id: '1',
            founded_at: storage.today,
            timezone: '',
            city: '',
            avatar: '',
            coach_id: storage.auth_id,
            coach_name: '',
            coach_phone: '',
            coach_email: '',
            subcoach_id: '0',
            subcoach_name: '',
            subcoach_phone: '',
            subcoach_email: '',
            auxiliar_id: '0',
            auxiliar_name: '',
            auxiliar_phone: '',
            auxiliar_email: ''
        },
        editForm: {
            csrf_token: storage.csrf_token,
            name: '',
            country: 'US',
            sport_id: '1',
            founded_at: '',
            timezone: '',
            city: '',
            avatar: '',
            coach_id: storage.auth_id,
            coach_name: '',
            coach_phone: '',
            coach_email: '',
            subcoach_id: 'none',
            subcoach_name: '',
            subcoach_phone: '',
            subcoach_email: '',
            auxiliar_id: 'none',
            auxiliar_name: '',
            auxiliar_phone: '',
            auxiliar_email: '',
            image: ''
        },
        errors: {

        },
        editErrors: {}
    },
    methods: {
        closeModal(){
            $('#createTeam').modal('hide');
            $('#editTeam').modal('hide');
            $('.modal-backdrop.in').remove();
        },
        onSubmit(){
            axios.post('', this.form)
                .then((res) => {
                    if(res.data.saved){
                        swal('Great!', 'Your team has been successfully created!', 'success')
                        setTimeout(location.reload.bind(location), 2500)
                    }
                })
                .catch((error) => {
                    console.log(error.response.data)
                    this.errors = error.response.data
                })
        },
        onUpdate() {
            axios.put('/teams/' + this.editForm.id, this.editForm)
                .then((res) => {
                    if(res.data.saved){
                        swal('Great!', 'Your team has been successfully updated!', 'success')
                        setTimeout(location.reload.bind(location), 2500)
                    }
                })
                .catch((error) => {
                    console.log(error.response.data)
                    this.editErrors = error.response.data
                })
        },
        getLocation(){
            navigator.geolocation.getCurrentPosition((position) => {
                var lat = position.coords.latitude
                var lng = position.coords.longitude
                axios.get('http://maps.googleapis.com/maps/api/geocode/json?latlng='+ lat +','+ lng +'&sensor=true')
                    .then((res) => {
                        const address = res.data.results[0]
                        this.form.city = address.address_components[1].long_name
                        this.form.country = address.address_components[4].short_name

                        axios.get('https://maps.googleapis.com/maps/api/timezone/json?location=' + lat + ',' + lng + '&timestamp=1458000000&key=AIzaSyBu-oadicxmzndvONC3xDJcIwWbd7NZTQ4')
                            .then((res) => {
                                this.form.timezone = res.data.timeZoneId
                                var milliseconds = (new Date).getTime();
                                // console.log(milliseconds)
                            })
                        // https://maps.googleapis.com/maps/api/timezone/json?location=38.908133,-77.047119&timestamp=1458000000&key=AIzaSyBu-oadicxmzndvONC3xDJcIwWbd7NZTQ4
                    })
            })
            // http://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&sensor=true
        },
        edit(id) {
            // console.log(id)
            axios.get('getTeam/' + id)
                .then((res) => {
                    this.editForm = res.data.team
                    this.editForm.csrf_token = this.form.csrf_token
                    this.editForm.image = ''
                })
        },
        imageChanged(event){
            this.form.avatar = ''
            this.showImage(event.target, 'output')
            var fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])

            fileReader.onload = (event) => {
                this.form.avatar = event.target.result
            }
        },
        editImageChanged(event){
            this.editForm.image = ''
            this.showImage(event.target, 'output2')
            var fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])

            fileReader.onload = (event) => {
                this.editForm.image = event.target.result
            }
        },
        showImage(input, output){
            var output = document.getElementById(output);
            output.src = ''
            var reader = new FileReader()
            reader.onload = function(){
              var dataURL = reader.result
              output.src = dataURL
            }
            reader.readAsDataURL(input.files[0])
        },
        deleteTeam(id){
            swal({
              title: 'Are you sure?',
              text: "After you delete your team, it can't be recovered",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                axios.delete('/teams/' + id)
                .then((res) => {
                    if(res.data.deleted) {
                        swal(
                            'Deleted!',
                            'The team was deleted successfully',
                            'success'
                        )
                        setTimeout(location.reload.bind(location), 2500)
                    }
                })
            })
        }
    },
    created(){
        this.getLocation()
    }

})
