import Vue from 'vue'
import axios from 'axios'
import Field from './components/Field'
import FieldHorizontal from './components/FieldHorizontal'

const app = new Vue({
    el: '#app',
    components: { Field, FieldHorizontal },
    data: {
        sport: {},
        events: [],
        lineups: [],
        lineup: {},
        positions: [],
        event: {
            id: 0,
            name: ''
        },
        form: {
            team_id: storage.team_id,
            csrf_token: storage.csrf_token,
            quantity: 8,
            players: [],
            event_id: 0
        }
    },
    mounted(){
        this.getData()
    },
    methods: {
        updatePosition(player, lineup_id, event){
            console.log(lineup_id)
            var grid = this.findGrid(event.target.value)
            var obj = {
                player,
                position: event.target.value,
                grid
            }
            axios.put(`/teams/${this.form.team_id}/lineup/${lineup_id}`, obj)
                .then((res) => {
                    if(res.data.updated){
                        toastr.success('Position updated!')
                    }
                })
                .catch((error) => toastr.error('Your changes could not be saved!', 'Please try again'))
        },
        findGrid(name){
            return this.positions.filter((position) => position.name == name)[0].position
        },
        submit(){
            axios.post('', this.form)
            .then((res) => {
                swal('Lineup created!', 'The page will be reloaded shortly.', 'success')
                setTimeout(location.reload.bind(location), 1000)
            }).catch((error) => {
                swal('Line Ups could not be created', 'Please make sure you selected the position for all players.', 'error')
            })
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
        setLineup(lineup){
            this.lineup = lineup
        },
        getData() {
            console.log('test')
            axios.get('getLineUp')
            .then((res) => {
                // console.log(res.data)
                this.events = res.data.events
                this.lineups = res.data.lineups
                if(res.data.lineups.length > 0){
                    this.lineup = JSON.parse(this.lineups[0].line_up)
                }
                this.sport = res.data.sport
                this.form.sport_id = this.sport.id
                this.setEvent(res.data.event)
                this.positions = JSON.parse(JSON.stringify(this.sport.positions))
                if(this.lineups.length > 0){
                    this.lineups.map((lineup) => {
                        lineup.line_up = JSON.parse(lineup.line_up)
                        return lineup
                    })
                }
            })
        },
        setEvent(event){
            this.event = event
            this.form.event_id = event.id
            this.form.players = event.players
            this.availability = event.availability
            this.closeModal()
        },
        closeModal(){
            $('#historyModal').modal('hide')
            $('.modal-backdrop.in').remove()
        }
    },
    filters: {
        eMoment(date){
            return moment(date).format('ddd DD MMM')
        }
    },
    computed: {
        filteredPositions(){
            var positions = this.positions.filter((position) => {
               // Busco por cada jugador si tiene esta misma posición,
               //  en caso de ser positivo, devuelvo el jugador
               // más no la posición
               var players = []
               this.form.players.forEach((player) => {
                if (typeof player.position !== "undefined" && player.position.grid == position.position){
                    players.push(player)
                }
               })
               if (players.length === 0){
                return position
               }
            })
            return positions
        }
    }
})
