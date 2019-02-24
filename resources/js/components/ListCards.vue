<template>   
    <div>
        <div id="taskAddModal" class="modal fade" >
            <div class="modal-dialog" style="margin-top: 250px;">
                <div class="modal-content" style="background: none; border: none;">
                     <div class="modal-body">                    
                        <add-card :nav="nav" :path="path" @addtask='addTask' @canceltask='cancelTask'  @deleteimage='deleteImage'></add-card>
                    </div>
                </div>
            </div>
        </div>



        <div id="imagesModal" class="modal fade">
            <div class="modal-dialog" style="margin-top: 0px;">
                <div class="modal-content">
                   


                    <div class="modal-body">
                        <div class="collage">
                            <a class="image" v-for="(url,key) in this.images" v-bind:style="{ left: 210*key+'px', transform: 'rotate('+(7*key)%(-15)+'deg)'}">
                               <img v-bind:src="url" class="img-rounded" alt="" @click="editUrl(url)">
                               <div class="img-close" @click="imgDelete(url)">&#10008;</div>
                            </a>                                      
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <download-component :nav="nav" @addimg='addImg'></download-component>                       
                    </div>
                </div>
            </div>
        </div>


        <input type="checkbox" id="settings-toggle" hidden>
        <nav class="settings-main">
            <label for="settings-toggle" class="settings-main-toggle" onclick @click="settingsClick"></label>
            <h2> 
                <a href="#">Настройки</a>
            </h2>

            <div class="setting-list">
                <div v-if='settingsShow'>
                    <slider :name="'angle'" :ru="'Угол'" :valueLoad="this.angle" :min="-90" :max="90" @changeangle='changeAngle'></slider>
                    <slider :name="'distance'" :ru="'Расстояние'" :valueLoad="this.distance" :min="0" :max="350" @changedistance='changeDistance'></slider>
                </div>
            </div>

            <div class="mask-content"></div>
        </nav>

        <div class="row">
            <div class="col-sm-12">
                <div class="task-header">
                    <h2 class="border-bottom text-center" :id="'navName_'+nav">{{folder_name}}</h2>            
                    <font-awesome-icon icon="plus-circle" class="icon-pointer" size="2x" color="#16a085" @click="createTask"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-toolbar justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <a type="button" class="btn btn-primary" href="#1" data-target="#carousel" data-slide-to="0">Задачи</a>
                        <a type="button" class="btn btn-primary" href="#2" data-target="#carousel" data-slide-to="1">Выполненные</a>
                    </div>                       
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">    
                <div class="background-side">
                    <div class="background-bottom">             
                        <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">             
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <div class="task-cards-list" v-if="showList">
                                        <div class="task-card" v-for="(task,key) in this.tasks" v-bind:id="'task_'+key" v-bind:style="{ left: (key*distance+60)+'px', transform: 'rotateY('+angle+'deg)', zIndex: '0'}" @mouseover="hover(key)" @mouseout="hoverNot(key, angle)">
                                            <task-card :task="task" :taskKey="key" :done="false" @tasks_reload='reload' @edit_img='editImg' @delete_img_task='deleteImgTask'></task-card> 
                                        </div>
                                    </div> 
                                </div>
                                <div class="carousel-item">
                                    <div class="task-cards-list" v-if="showList">
                                        <div class="task-card" v-for="(task,key) in this.tasksDone" v-bind:id="'task_'+key" v-bind:style="{ left: (key*distance+60)+'px', transform: 'rotateY('+angle+'deg)'}" @mouseover="hover(key)" @mouseout="hoverNot(key, angle)">
                                            <task-card :task="task" :taskKey="key" :done="true" @tasks_reload='reload'></task-card> 
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div> 


        </div> 

</template>

<script>
    export default {
        props:['tasks_load', 'tasks_done_load', 'folder_name', 'nav', 'angle_load', 'distance_load', 'images_load'], 
        data(){
            return{

                tasks: this.tasks_load,                
                tasksDone: this.tasks_done_load,
                images: this.images_load,
                taskLeft: '100px',                
                showList:true,
                path:'',                
                
                editImgId:-1,

                settingsShow:false,

                angle: this.angle_load,
                distance: this.distance_load,


            }
        },
         mounted(){

            $('.task-cards-list').bind('mousemove', function(e) { 
                    this.scrollLeft = e.clientX/900*(this.scrollWidth + 30 - this.clientWidth);             
            });
            $('.collage').bind('mousemove', function(e) {         
                    this.scrollLeft = (e.clientX-440)/470*(this.scrollWidth + 30 - this.clientWidth);
            });

        },
        methods:{
            
            imgDelete(url){                
                axios.post('/navs/image/delete', {
                    url: url,
                    nav: this.nav
                }).then(response => (
                    this.images = response.data.images
                    )).catch(error => {
                    console.log(error);
                })
            },

            settingsClick(){
                setTimeout(() => {
                    this.settingsShow = true
                }, 500);
            },
            createTask(){
                $("#taskAddModal").modal('show');
            },
            addTask(data){
                this.tasks = data.tasks_load;
                this.path = '';
            },
            cancelTask(data){
                this.path = '';
            },

            reload(data){
                var tasks = JSON.parse(data.tasks),
                    tasks_done = JSON.parse(data.tasks_done);
                this.showList = false;
                if(tasks.length>0)
                    this.tasks = tasks;
                if(tasks_done.length>0)
                    this.tasksDone = tasks_done;
               
                setTimeout(() => {
                    this.showList = true;
                }, 0);                
            },
            hover(key){
                $('#task_'+key).css({'transform':'matrix(1, 0, 0, 1, 0, 0)', 'zIndex':'1'});
            },
            hoverNot(key, angle){               
                $('#task_'+key).css({'transform':'rotateY('+angle+'deg)', 'zIndex':'0'});                
            },

            addImg(data){                
                this.images = data.images_load;
            },
            deleteImage(data){
                this.path = '';
            },
            deleteImgTask(data){                
                this.tasks[data.task_key].path='';
            },
            editImg(data){                
                this.editImgId = data.task_key;
            },
            editUrl(url){               
                if(this.editImgId>=0)
                    this.tasks[this.editImgId].path=url;
                else
                    this.path=url;
                this.editImgId=-1;
                $("#imagesModal").modal('hide');
            },
            changeAngle(data){
                this.angle = data.value;
            },
            changeDistance(data){
                this.distance = data.value;
            }

        }
 

    }
</script>
