import Vue from 'vue'
import axios from 'axios'
import jquery from 'jquery'

new Vue({
    el: '#app',
    data: {
        user_email: storage.user_email,
        isOwner: storage.isOwner,
        form: {
            team_id: storage.team_id,
            csrf_token: storage.csrf_token
        },
        players: [],
        availability: [],
        event: {
            id: 0,
            name: ''
        },
        events: [],
        statuses: [
            {
                id: 1,
                classes: {
                    'fa-window-close': true,
                    'text-danger': true
                }
            },
            {
                id: 2,
                classes: {
                    'fa-question': true,
                    'text-info': true
                }
            },
            {
                id: 3,
                classes: {
                    'text-success': true,
                    'fa-check': true
                }
            }
        ]
    },
    created(){
        this.getPlayers()
        this.getData()
    },
    methods: {
        isParent(player){
            (this.user_email == player.email) ? true : false
        },
        markAllGames(player){
            this.events.forEach((event) => {
                this.changeStatus(player, event.id, 3, false)
            })
            this.getData()
            toastr.success('Player marked as available for all games!')
        },
        changeStatus(player, eventId, status, notify = true){
            axios.post(`availability`, { player_id: player.id, status: parseInt(status), event_id: eventId})
            .then((res) => {
                var availability = this.availability.filter((availability) => {
                    if(availability.player_id === player.id){
                        // console.log(availability)
                        return availability
                    }
                })

                if(availability.length > 0){
                    availability[0].status = parseInt(status)
                }else{
                    this.availability.push({player_id: player.id, status: parseInt(status)})
                }
                this.getData()
                if(notify){
                    toastr.success('Position changed!')
                }
            })
            .catch((error) => console.log(error))
        },
        setEvent(event){
            this.event = event
            this.availability = event.availability
            this.closeModal()
        },
        closeModal(){
            $('#historyModal').modal('hide')
            $('.modal-backdrop.in').remove()
        },
        isAvailable(player){
            var availability = this.availability.filter((availability) => {
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
        },
        getPlayers(){
            axios.get(`/teams/${this.form.team_id}/ajax`)
                .then((res) => {
                    this.players = res.data
                })
                .catch((error) => console.log(error))
        },
        getData(){
            axios.get(`/teams/${this.form.team_id}/getAvailability`)
                .then((res) => {
                    // console.log('qbta')
                    this.availability = res.data.availability
                    this.event = res.data.event
                    this.events = res.data.events
                })
                .catch((error) => console.log(error))
        },
        eventClass(player, event){
            switch (this.eventStatus(player, event)){
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
            return 1
        }
    },
    filters: {
        eMoment(date){
            return moment(date).format('ddd DD MMM')
        }
    },
    computed: {
        isDisabled() {
            if(this.event.id === 0){
                return false
            }
            return false
        }
    },
    filters: {
        moment(date) {
            return moment(date).format('DD-MMM, YYYY')
        }
    }
})
