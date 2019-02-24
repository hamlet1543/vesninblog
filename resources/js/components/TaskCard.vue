<template>
    <div>
        <transition v-bind:name="transitionName">
        <div v-if="show">
            <div class="card" v-bind:style="{background : background}">
                <div class="card-body">
                    <div class="card-content">

                        <div class="card-img" v-if="edit || this.task.path">                           
                            <div class="image" v-if="this.task.path">
                                <img v-bind:src="this.task.path" > 
                                <font-awesome-icon icon="times-circle" class="img-close" color="#16a085" v-if="edit" @click="deleteImgTask"/>
                            </div>
                            <div v-if="!this.task.path">
                                Изображение
                                <font-awesome-icon icon="plus-circle" class="icon-pointer" color="#16a085" @click="addImage"/>           
                            </div>                   
                        </div>

                        <div class="card-text">

                            <div class="text-edit" v-if="!nameEdit">
                                {{name}}
                                <font-awesome-icon icon="pencil-alt" class="edit" color="#16a085" v-if="!this.done" @click="nameEdit = !nameEdit; edit = true"/>
                            </div>
                            <div v-if="nameEdit">
                                <input type="text" name="name" id="t_name" class="form-control" placeholder="Наименование" v-model="name">
                            </div>

                            <div class="text-edit" v-if="!descriptionEdit">
                                {{description}}
                               <font-awesome-icon icon="pencil-alt" class="edit" color="#16a085" v-if="!this.done" @click="descriptionEdit = !descriptionEdit; edit = true"/>
                            </div>
                            <div v-if="descriptionEdit">
                                <textarea  type="textarea" name="description" id="t_description" class="form-control" v-model="description" placeholder="Описание" style="height: 200px;">{{description}}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" style="float: left;" @click="editTask" v-if="edit">Сохранить</button>
                        <button class="btn btn-danger" style="float: right;"@click="cancel" v-if="edit">Отменить</button>

                        <button class="btn btn-success" style="float: left;" @click="doneTask" v-if="!edit&&!this.done">Выполнено</button>
                        <button class="btn btn-warning" style="float: left;" @click="notDoneTask" v-if="!edit&&this.done">Не выполнено</button>
                        <button class="btn btn-danger" style="float: right;"@click="deleteTask" v-if="!edit">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props:['task', 'taskKey', 'done'], 
        data(){
            return{
            showPath:'',
             show: true,
             name: this.task.name,
             description: this.task.description,
             path: this.task.path,
             url:false,           
             transitionName: "",
             background:'#fff',

             edit: false,
             nameEdit:false,             
             descriptionEdit:false,
            }
        },
        mounted(){
            
        },        
        methods:{
            doneTask(){
                    this.transitionName = "makecard";
                    this.background = '#28a745';
                    axios.post('/tasks/'+this.task.id+'/done').then(response => ( 
                        this.show = false,
                        setTimeout(() => {
                        this.$emit('tasks_reload', {
                            tasks: response.data.tasks,
                            tasks_done: response.data.tasks_done
                        }) 
                    }, 500)
                    ));
         
            },
            notDoneTask(){
                    this.transitionName = "makecard";
                    this.background = '#dc3545';
                    axios.post('/tasks/'+this.task.id+'/notdone').then(response => ( 
                        this.show = false,
                        setTimeout(() => {
                        this.$emit('tasks_reload', {
                            tasks: response.data.tasks,
                            tasks_done: response.data.tasks_done
                        }) 
                    }, 500)
                    ));
         
            },
            deleteTask(){
                this.transitionName = "deletecard";
                axios.delete('/tasks/'+this.task.id).then(response => (
                    this.show = false,
                    setTimeout(() => {
                        this.$emit('tasks_reload', {
                            tasks: response.data.tasks,
                            tasks_done: response.data.tasks_done
                        }) 
                    }, 500)
                ));
            },
            editTask(){
                    axios.post('/tasks/'+this.task.id+'/edit',{
                        path: this.task.path,
                        name: this.name,
                        description: this.description
                    }).then(response => (
                        this.path = $("#t_image").val(),
                        this.edit = false,
                        this.nameEdit = false,
                        this.descriptionEdit = false 
                    ));
         
            },
            cancel(){
                this.edit = false;
                this.nameEdit = false;             
                this.descriptionEdit = false;   
            },
            addImage(){  
            console.log(this.taskKey);              
                this.$emit('edit_img',{
                    task_key: this.taskKey
                })
                $("#imagesModal").modal('show');
                this.url = true;
            },
            deleteImgTask(){                
                this.$emit('delete_img_task',{
                    task_key: this.taskKey                   
                })
            }           
        }

    }
</script>
