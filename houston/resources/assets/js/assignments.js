import Vue from 'vue'
import axios from 'axios'
import jquery from 'jquery'

new Vue({
    el: '#app',
    data: {
        isOwner: storage.isOwner,
        form: {
            team_id: storage.team_id,
            csrf_token: storage.csrf_token
        },
        players: [],
        assignments: [],
        events: []
    },
    created(){
        this.getData()
        this.getPlayers()
        setTimeout(() => {
            $('.editable').editable({
                savenochange: true
            })
        }, 1000)
    },
    methods: {
        closeModal(){
            $('#historyModal').modal('hide')
            $('.modal-backdrop.in').remove()
        },
        playerClass(player){
            switch (player.status){
                case 1:
                    return {
                        'fa-window-close': true,
                        'text-danger': true
                    }
                break
                case 2:
                    return {
                        'fa-question': true,
                        'text-info': true
                    }
                break
                case 3:
                    return {
                        'text-success': true,
                        'fa-check': true
                    }
                break
            }
        },
        hasAssignment(player, event){
            var assignment = event.assignments.filter((assignment) => {
                if(assignment.player_id === player.id){
                    // console.log(assignment.details)
                    return assignment
                }
                return
            })[0]
            if(assignment){
                return assignment.details
            }
            return false
        },
        getData(){
            axios.get(`/teams/${this.form.team_id}/getAssignments`)
                .then((res) => {
                    this.assignments = res.data.assignments
                    this.events = res.data.events
                })
                .catch((error) => console.log(error))
        },
        getPlayers(){
            axios.get(`/teams/${this.form.team_id}/ajax`)
                .then((res) => {
                    this.players = res.data
                })
                .catch((error) => console.log(error))
        },
        eventStatus(player, event) {
            var availability = event.availability.filter((availability) => {
                if(availability.player_id === player.id){
                    // console.log(availability)
                    return availability
                }
                return
            })[0]
            if(availability){
                // console.log(availability.status)
                return availability.status
            }
            return 0
        }
    },
    filters: {
        eMoment(date){
            return moment(date).format('ddd DD MMM')
        },
        moment(date) {
            return moment(date).format('DD-MMM')
        }
    }
})
