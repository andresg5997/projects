<template>
    <div class="field">
        <div class="f-col" v-for="row in rows">
            <div class="f-row" v-for="col in cols">
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
        padding: 30px 30px;
        display: flex;
        background-image:url('/fields/soccer_horizontal.png');
        background-repeat:no-repeat;
        background-size: 100% 100%;
        /*background-size: contain;*/
        background-position:center center;
        height:414px;
        width:100%;
        justify-content: space-between;
        flex-basis: 0;
    }

    .f-center{
        display: flex;
        justify-content: center
    }

    .f-col{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-basis: 0;
    }

    .f-row{
        flex-basis: 0;
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
