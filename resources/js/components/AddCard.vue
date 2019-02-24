<template>
<div class="card">
    <div class="card-body">
        <div class="card-content">

            <div class="card-img">                           
                <div class="image" v-if="path">
                    <img v-bind:src="path" >
                    <font-awesome-icon icon="times-circle" class="img-close" color="#16a085" @click="deleteImage"/>   
                </div>
                <div v-if="!path">
                    Изображение
                    <font-awesome-icon icon="plus-circle" class="icon-pointer" color="#16a085" @click="addImage"/>
                </div>                
            </div>

            <div class="card-text">
                <input type="text" class="form-control" placeholder="Наименование" v-model="nameAdd">
                <textarea  type="textarea" class="form-control" v-model="descriptionAdd" placeholder="Описание" style="height: 200px;"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success" style="float: left;" @click="addTask">Сохранить</button>
            <button class="btn btn-danger" style="float: right;"@click="cancel">Отменить</button>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props:['nav', 'path'], 
        data(){

            return{
                nameAdd:'',
                descriptionAdd:'',
                             

            }
        },
        methods:{
            addImage(){                
                $("#imagesModal").modal('show');
            },
            deleteImage(){                
                this.$emit('deleteimage');
            },
            addTask(){
                axios.post('/tasks/add', {
                    nav: this.nav,
                    path: this.path,
                    name: this.nameAdd,
                    description: this.descriptionAdd 
                }).then(response => (
                    this.nameAdd = '',
                    //this.path = '',
                    this.descriptionAdd = '',
                    this.$emit('addtask',{
                        tasks_load: JSON.parse(response.data.tasks)
                    }),                    
                    $("#taskAddModal").modal('hide')
                ));
            },
            cancel(){                
                this.$emit('canceltask'),
                this.nameAdd = '',
                this.descriptionAdd = '',
                $("#taskAddModal").modal('hide');
            }
        }
    }
   
</script>
