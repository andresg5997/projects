<template>
    <div class="field">
        <div class="f-col" v-for="col in cols">
            <div class="f-row" v-for="row in rows">
                <span class="position" v-if="findPosition(col+row)" v-text="findPosition(col+row)"></span>
                <br>
                <span class="player" v-if="findPlayer(col+row)" v-html="findPlayer(col+row)"></span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['lineup'],
        data(){
            return {
                cols: ['A', 'B', 'C', 'D', 'E'],
                rows: [1,2,3,4,5,6]
            }
        },
        mounted(){

        },
        methods: {
            findPosition(position){
                var lineup = this.lineup.filter((lineup) => position === lineup.grid)
                if(lineup.length > 0){
                    return lineup[0].position
                }
                return false
            },
            findPlayer(position){
                var lineup = this.lineup.filter((lineup) => position === lineup.grid)
                if(lineup.length > 0){
                    return lineup[0].first_name + '<br>' + lineup[0].last_name
                }
                return false
            }
        }
    }
</script>

<style>
    span.player{
    color: white;
    }

    div.field{
        padding: 10px 20px;
        display: flex;
        background-image:url('/fields/soccer.png');
        background-repeat:no-repeat;
        background-size: 100% 100%;
        background-position:center center;
        height:400px;
        width:100%;
    }

    .f-center{
        display: flex;
        justify-content: center
    }

    .f-col{
        display:flex;
        flex-grow: 1;
        margin: 2px;
        flex-direction: column;
        text-align:center;
        flex-basis: 0;
    }

    .f-row{
        flex: 1;
        justify-content: space-between;
        margin: 2px 0px;
        flex-basis: 0;
        flex-grow: 1;
    }

    span.position{
        color: rgba(0,0,0,0.8);
        background: rgba(255,255,255,0.8);
        border-radius: 15px;
        /*border: 1px solid black;*/
        font-weight:600;
        padding: 5px;
        align-self: center;
        text-transform: uppercase;
    }
</style>
