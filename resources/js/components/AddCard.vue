<template>
    <div>
    <transition v-bind:name="qwerty">


    <div class="card" v-if="show">
           
    <div style="height: 100%;" >
        <div style="height: 30%;">
            <download-component></download-component>
        </div>
        <div style="height: 70%;">
            <div style="height: 90%; text-align: left;">
                <div class="my-2">
                    <input type="text" name="name" id="t_name" class="form-control" placeholder="Наименование">
                </div> 
                
                <div class="my-2">
                    <textarea type="textarea" name="description" id="t_description" class="form-control" placeholder="Описание" style="height: 200px;"></textarea>
                </div> 

            </div>
            
        </div>
    </div>
    </div>

</transition>


<div style="height: 10%;">
                <button  class="btn btn-success" style="float: left;">Добавить</button>

                <a class="btn btn-danger" style="float: right;" id="delete" @click="deleteTask">Отменить</a>
            </div>
    </div>

</template>

<script>
    export default {
        props:[], 
        data(){

            return{
                qwerty:'addcard',
                show:false
                             

            }
        },
        methods:{
            deleteTask(){
            console.log(0);
                this.qwerty = 'addcard'; 
                this.show = !this.show;
                console.log(this.qwerty);
            }
        }
    }
   
</script>
