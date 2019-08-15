import axios from 'axios'
import Vue from 'vue'
import { VueMaskDirective } from 'v-mask'

Vue.directive('mask', VueMaskDirective);

const app = new Vue({
    el: '#app',
    data: {
        updatePicture: {
            csrf_token: storage.csrf_token,
            user_email: storage.user_email,
            picture: '',
            player_id: 0
        },
        form: {
            csrf_token: storage.csrf_token,
            number: '',
            first_name: '',
            last_name: '',
            teacher: '',
            gender: 'M',
            phone: '',
            email: '',
            parent_name: '',
            team_id: storage.team_id,
            picture: ''
        },
        editPlayer: false,
        errors: {},
        players: {}
    },
    methods: {
        emptyForm(){
            let playerForm = document.getElementById('playerForm')
            playerForm.reset()
            let csrf_token = this.form.csrf_token
            let team_id = this.form.team_id

            let form = {
                csrf_token: csrf_token,
                number: '',
                first_name: '',
                last_name: '',
                teacher: '',
                gender: 'M',
                phone: '',
                email: '',
                parent_name: '',
                team_id: team_id,
                picture: ''
            }
            this.form = form

            var output = document.getElementById('output');
            output.src = ''
        },
        sendPicture() {
            axios.put('/updatePicture', this.updatePicture)
                .then((res) => {
                    if(res.data.saved){
                        swal('Great!', 'Picture uploaded!', 'success')
                        this.getData()
                    }
                })
                .catch((error) => {
                    swal('Error!', 'Please try again.', 'error')
                })
        },
        newPicture(event) {
            this.updatePicture.picture = ''
            var fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])

            fileReader.onload = (event) => {
                this.updatePicture.picture = event.target.result
            }
        },
        imageChanged(event){
            this.form.picture = ''
            this.showImage(event.target)
            var fileReader = new FileReader()
            fileReader.readAsDataURL(event.target.files[0])

            fileReader.onload = (event) => {
                this.form.picture = event.target.result
            }
        },
        showImage(input){
            var output = document.getElementById('output');
            output.src = ''
            var reader = new FileReader()
            reader.onload = function(){
              var dataURL = reader.result
              output.src = dataURL
            }
            reader.readAsDataURL(input.files[0])
        },
        playerEdit(player) {
            this.editPlayer = true
            this.form = JSON.parse(JSON.stringify(player))
            var output = document.getElementById('output');
            output.src = ''
            if(player.picture){
                output.src = '/storage/pictures/' + player.picture
            }
            this.openModal()
        },
        updatePlayer(id) {
            axios.put('/teams/' + this.form.team_id + '/roster/' + id, this.form)
                .then((res) => {
                    if(res.data.updated){
                        swal('Success!', 'The player was successfully updated.', 'success');
                        this.errors = {}
                        this.editPlayer = false;
                        this.getData()
                        this.closeModal()
                    }
                })
                .catch((error) => setTimeout(() => this.errors = error.response.data, 500))
        },
        closeModal() {
            $('#rosterModal').modal('hide')
            $('#importModal').modal('hide')
            $('.modal-backdrop.in').remove()
            this.editPlayer = false
            this.emptyForm()
        },
        storePlayer() {
            axios.post('', this.form)
            .then((res) => {
                swal('Success!', 'The player was successfully registered.', 'success');
                this.errors = {}
                this.getData()
                this.closeModal();
            })
            .catch((error) => setTimeout(() => this.errors = error.response.data, 500))
        },
        openModal() {
            $('#rosterModal').modal('show');
        },
        setPlayerId(id) {
            this.updatePicture.player_id = id
        },
        getData() {
            axios.get('ajax')
            .then((res) => {
                // console.log(res.data)
                this.players = res.data
            })
        },
        deletePlayer(id) {
            swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                axios.delete('roster/' + id)
                .then(() => {
                    swal(
                        'Deleted!',
                        'The player was deleted successfully',
                        'success'
                    )
                    var player = app.players.filter((player) => player.id == parseInt(id))[0]
                    app.players.splice(app.players.indexOf(player), 1)
                    // app.getData();
                    app.closeModal();
                })
            })
        }
    },
    mounted() {
        this.getData()
    }
})
