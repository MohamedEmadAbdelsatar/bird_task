<template>
    <main class="container">
        <h2>Bird Task</h2>
        <center>

                <div class="row align-items-center form">
                    <div class="col-3"> <label for="name">Enter User Name:</label></div>
                    <div class="col-6">
                        <input type="text" id="name" v-model="name" class="form-control" />
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary" @click="getScore">Find!</button>
                    </div>

                </div>

        </center>
        <br>
        <div v-if="!firstTime">
            <table class="table" v-if="!empty">
                <thead>
                    <tr class="table-dark">
                        <th>Type</th>
                        <th>Repository</th>
                        <th>Date</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.date">
                        <td> {{ event.type}} </td>
                        <td> {{ event.repo}} </td>
                        <td> {{ event.date}} </td>
                        <td> {{ event.points}} </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="table-dark">{{ sumPoints }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-else>No Events Found!</p>
        </div>
    </main>

</template>

<script>
export default {
    data(){
        return {
            name: "",
            events:[],
            empty: true,
            firstTime: true,
        }
    },
    computed:{
        sumPoints(){
            var sum = 0;
            for(var i=0; i<this.events.length; i++){
                sum += this.events[i].points;
            }
            return sum;
        }
    },
    methods:{
        async getScore(){
            await this.axios.post('/api/get-events', {name: this.name,}).then(response =>{
                this.firstTime = false;
                if(response.data == 'Not Found'){
                    this.empty = true;
                }else {
                    this.events = response.data.elements;
                    this.empty = false;
                }


            })
        }
    }
}
</script>

<style>
    main{
        align-content: center;
        text-align: center;
        font-size: 18px;
    }
    .form{
        border: 1px solid black;
        box-shadow: 2px;
        width: 80%;
        padding: 20px;
        border-radius:15px;
    }
</style>
