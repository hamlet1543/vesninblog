<template>
    <div class="container">
        <div class="card" id="1" style="height: 100%;" >
            <img class="card-img-top" src=".../100px180/" alt="Card image cap" style="height: 30%;">
            <div class="card-body" style="height: 70%;">
                <div style="height: 80%; text-align: left;">
                    <h5 class="card-title">{{name}}</h5>
                    <p class="card-text">{{description}}</p>
                </div>

                <div style="height: 20%;">
                    <input type="button" class="btn btn-success" @click="makeTask">Выполнено</a>
                    <input type="button" class="btn btn-danger" @click="deleteTask">Отменить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['task'],
        data(){
            return
                {
                    id: "",
                    name: "",
                    description:""



                }

        },
        mounted() {
            //this.task_id="task_" + this.task.id"
            console.log(this.task_id);

        },
        methods:{
            makeTask(){
                console.log('OK');
            },
            deleteTask(){
                console.log('delete');
            },
        }
    }
</script>
