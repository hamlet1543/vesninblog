<template>
    <div>
        <transition v-bind:name="transitionName">
        <div v-if="show">
            <div class="card" v-bind:style="{background : background}">
                <div class="card_body">
                    <div class="card_content">
                        <div class="card_img" v-if="edit||this.task.path">                           
                            <div class="image" v-if="this.task.path&&this_url">
                                <img v-bind:src="this.task.path" > 
                                <div class="img-close" @click="deleteImage" v-if="edit">&#10008;</div>                              
                            </div>
                            <div class="image_edit" v-if="!this.task.path||!this_url">
                                <div class="col-sm-12">
                                    <div class="task_header">
                                        Изображение            
                                        <div class="circle-plus circle-img" @click="addImage">
                                            <div class="circle"></div>
                                            <div class="horizontal"></div>
                                            <div class="vertical"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--                             <download-component :path="path" :task_id="this.task.id" :nav="nav.id" @delete-img='edit = true'></download-component>
                         -->                        
                        </div>
                        <div class="card_text">

                            <div class="name" v-if="!name_edit">
                                {{name}}
                                <div class="edit" v-if="!this.done" @click="name_edit = !name_edit; edit = true">&#9998;</div>
                            </div>
                            <div class="name_edit" v-if="name_edit">
                                <input type="text" name="name" id="t_name" class="form-control" placeholder="Наименование" v-model="name">
                            </div>

                            <div class="description" v-if="!description_edit">
                                {{description}}
                                <div class="edit" v-if="!this.done" @click="description_edit = !description_edit; edit = true">&#9998;</div>
                            </div>
                            <div class="description_edit" v-if="description_edit">
                                <textarea  type="textarea" name="description" id="t_description" class="form-control" v-model="description" placeholder="Описание" style="height: 200px;">{{description}}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="card_footer">
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
        props:['task', 'taskKey', 'done', 'nav', ], 
        data(){
            return{
            showPath:'',
             show: true,
             this_url: true,
             id: 'task_' + this.task.id,
             name: this.task.name,
             description: this.task.description,
             path: this.task.path,
             url:false,           
             transitionName: "",
             background:'#fff',

             edit: false,
             name_edit:false,             
             description_edit:false,
            }
        },
        mounted(){
            
        },
        watch:{
            task: function () {               
               this.this_url = true
            }
        },
        methods:{
            doneTask(){
                    this.transitionName = "makecard";
                    this.background = '#28a745';
                    axios.post('/tasks/'+this.task.id+'/done').then(response => ( 
                        this.show = false,
                        setTimeout(() => {
                        this.$emit('tasksreload', {
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
                        this.$emit('tasksreload', {
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
                        this.$emit('tasksreload', {
                            tasks: response.data.tasks
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
                        this.name_edit = false,
                        this.description_edit = false 
                    ));
         
            },
            cancel(){
                this.edit = false;
                this.name_edit = false;             
                this.description_edit = false;   
            },
            addImage(){
                console.log('addImage');
                this.$emit('editimg',{
                    task_key: this.taskKey
                })
                $("#imagesModal").modal('show');
                this.url = true;
            },
            deleteImage(url){
                axios.post('/tasks/'+this.task.id+'/image/delete').then(response => (
                   this.this_url = false

                    )).catch(error => {
                    console.log(error);
                })
            }
        }

    }
</script>
