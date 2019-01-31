<template>
    <div>

                <div id="taskAddModal" class="modal fade" >
            <div class="modal-dialog" style="margin-top: 250px;">
                <div class="modal-content" style="background: none; border: none;">
                     <div class="modal-body">
                    

<!--             <transition name="addcard">

                <div v-if="show"> -->
                     <div class="card">
                        <div class="card_body">
                            <div class="card_content">

                                <div class="card_img">                           
                                    <div class="image" v-if="this_path">
                                        <img v-bind:src="this_path" > 
                                        <div class="img-close" @click="this_path=''">&#10008;</div>                              
                                    </div>
                                    <div class="image_edit" v-if="!this_path">
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
                                </div>

                                <div class="card_text">

                                    <div class="name_edit">
                                        <input type="text" name="name" id="t_name" class="form-control" placeholder="Наименование" v-model="nameAdd">
                                    </div>


                                    <div class="description_edit">
                                        <textarea  type="textarea" name="description" id="t_description" class="form-control" v-model="descriptionAdd" placeholder="Описание" style="height: 200px;"></textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="card_footer">
                                <button class="btn btn-success" style="float: left;" @click="addTask">Сохранить</button>
                                <button class="btn btn-danger" style="float: right;"@click="cancel">Отменить</button>
                            </div>
                        </div>
                    </div>

<!--                 </div>
            </transition> -->
                           
                </div>
                </div>
            </div>
        </div>



        <div id="imagesModal" class="modal fade">
            <div class="modal-dialog" style="margin-top: 0px;">
                <div class="modal-content">
                    <!-- Заголовок модального окна -->
<!--                     <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Заголовок модального окна</h4>
</div> -->
                    <!-- Основное содержимое модального окна -->
                    <div class="modal-body">
                        <div class="collage">
                            <a class="image" v-for="(url,key) in this.images_this" v-bind:style="{ left: 210*key+'px', transform: 'rotate('+(7*key)%(-15)+'deg)'}">
                               <img v-bind:src="url" class="img-rounded" alt="" @click="editUrl(url)">
                               <div class="img-close" @click="imgDelete(url)">&#10008;</div>
                            </a>                                      
                        </div>
                    </div>
                    <!-- Футер модального окна -->
                    <div class="modal-footer">
                        <div class="progress" style="height: 40px;" v-if="showDownload">
                            <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">{{ fileCurrent }}</div>
                        </div>
                        <input type="file" name="image" v-show="false" @change="sync" ref="imgBtn" >
                        {{ imgName }}
                        <button type="button" class="btn btn-success" @click="download" v-if="!showDownload && imgName"><i class="fa fa-plus"></i>Загрузить</button>
                        <button type="button" name="image" class="btn btn-warning" @click="cloneImgBtn">Выберите файл</button>

                       <!--  <div class="custom-file">
                            <input type="file" class="custom-file-input btn-success" id="validatedCustomFile" required>
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>                           
                        </div> -->
                        <!-- <button type="button" class="btn btn-default form-control" @click="download"><i class="fa fa-plus"></i>Загрузить</button>
                        <button type="file" name="image" class="btn btn-success" @change="sync">Выберете файл</button> -->

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Отменить</button>


                    </div>
                </div>
            </div>
        </div>




        <input type="checkbox" id="settings-toggle" hidden>
        <nav class="settings-main">
            <label for="settings-toggle" class="settings-main-toggle" onclick @click="settingSlider"></label>
            <h2> 
                <a href="#">Настройки</a>
            </h2>

            <div class="setting_list">
                <div v-if='settings_show'>
                    <div class="setting">    
                        <h4>Угол: {{ value_angle }}</h4>
                            <div class="save" @click="editAngle">&#10004;</div>
                            <div class="cancel" @click="value_angle = angle_this">&#10008;</div>
                    </div>   
                    <input type="text" v-model="value_angle" v-on:input="sync"> 
                    <vue-slider                         
                        v-model="value_angle"
                        v-bind="options_angle"
                       
                      ></vue-slider>                     
                </div>

                 <div v-if='settings_show'>
                    <div class="setting">
                        <h4>Расстояние: {{ value_distance }}</h4>
                        <div class="save" @click="editDistance">&#10004;</div>
                        <div class="cancel" @click="value_distance = distance_this">&#10008;</div>
                    </div>   
                    <vue-slider
                        v-model="value_distance"
                        v-bind="options_distance"
                      ></vue-slider>
                </div>


            </div>

            <div class="mask-content"></div>
        </nav>

        <div class="row">
            <div class="col-sm-12">
                <div class="task_header">
                    <h2 class="border-bottom text-center">{{nav.name}}</h2>            
                    <div class="circle-plus" @click="createTask">
                        <div class="circle"></div>
                        <div class="horizontal"></div>
                        <div class="vertical"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-toolbar justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <a type="button" class="btn btn-primary" href="#1" data-target="#carousel" data-slide-to="0">Задачи</a>
                        <a type="button" class="btn btn-primary" href="#2" data-target="#carousel" data-slide-to="1">Выполненные</a>
<!--                         <a type="button" class="btn btn-primary" href="#3" data-target="#carousel" data-slide-to="2">Изображения</a> -->
                        
                    </div>                       
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">    
                <div class="background_side">
                    <div class="background_bottom">             
                        <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">             
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <div class="task_cards_list" v-if="show_list">
                                        <div class="task_card" v-for="(task,key) in this.tasks_this" v-bind:id="'task_'+key" v-bind:style="{ left: (key*value_distance+60)+'px', transform: 'rotateY('+value_angle+'deg)'}">
                                            <task-card :task="task" :taskKey="key" :done="false" :nav="nav" @tasksreload='reload' @editimg='editImg'></task-card> 
                                        </div>
                                    </div> 
                                </div>
                                <div class="carousel-item">
                                    <div class="task_cards_list" v-if="show_list">
                                        <div class="task_card" v-for="(task,key) in this.tasks_done_this" v-bind:id="'task_'+key" v-bind:style="{ left: (key*value_distance+60)+'px', transform: 'rotateY('+value_angle+'deg)'}">
                                            <task-card :task="task" :taskKey="key" :done="true" :nav="nav" @tasksreload='reload'></task-card> 
                                        </div>
                                    </div>
                                </div>
<!--                                 <div class="carousel-item">
    <div class="collage">
       <a v-for="(url,key) in this.images_this" v-bind:style="{ left: (210+70*7*key)%630+'px', top: (50+50*3*key)%450+'px', transform: 'rotate('+(15-(7*key)%30)+'deg)'}">
           <img v-bind:src="url" class="img-rounded" alt="" >
       </a>
      
    </div>
</div> -->
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div> 


        </div> 

</template>

<script>
    import vueSlider from 'vue-slider-component'
    export default {
        props:['tasks', 'tasks_done', 'nav', 'angle', 'distance', 'images'], 
        data(){
            return{
                showDownload:false,
                imgName:'',
                //csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                tasks_this: '',
                this_path:'',
                tasks_done_this: '',
                images_this:'',
                taskLeft: '100px',
                show:false,
                show_list:true,
                path:'',
                name:'',
                is_src:false,
                url:'',
                nameAdd:'',
                descriptionAdd:'',
                editImgId:0,

                angle_this:this.angle,
                distance_this:this.distance,
                settings_show:false,

                value_angle: this.angle,
                options_angle: {
                eventType: 'auto',
                width: 'auto',
                height: 6,
                dotSize: 34,               
                min: -90,
                max: 90,
                interval: 1,               
                speed: 0.5,
                show: true            
            },
            value_distance: this.distance,
              options_distance: {
                eventType: 'auto',
                width: 'auto',
                height: 6,
                dotSize: 34,               
                min: 0,
                max: 350,
                interval: 1,               
                speed: 0.5,
                show: true        
              },

            fileProgress: 0,
            fileCurrent: '',

            }
        },
        components: {
            vueSlider
        },
         mounted(){

            this.tasks_this = this.tasks;
           
            this.tasks_done_this = this.tasks_done;
            this.images_this = this.images;
            if(this.url!='')
                this.is_src=true;
            $('.task_cards_list').bind('mousemove', function(e) { 
                    this.scrollLeft = e.clientX/900*(this.scrollWidth + 30 - this.clientWidth);             
            });
            $('.collage').bind('mousemove', function(e) {         
                    this.scrollLeft = (e.clientX-440)/470*(this.scrollWidth + 30 - this.clientWidth);
            });

            $( document ).ready(()=>{
                var angle=0;
                $('.task_card').hover(function(){
                    angle = $(this).css('transform');
                    $(this).css({'transform':'matrix(1, 0, 0, 1, 0, 0)'});
                },
                function(){
                    $(this).css({'transform':angle, 'zIndex':'0'});
                });
            });

        },
        methods:{
            cloneImgBtn($event){
                const elem = this.$refs.imgBtn;
                elem.click();                
            },
            sync(e) {
                e.preventDefault();
                this.image = e.target.files[0];
                this.imgName = e.target.files[0].name;

            },
            async download(){
                this.showDownload = true;
                let data = new FormData()
                data.append('image', this.image)

                await axios.post('/tasks/'+this.nav.id+'/image/upload', data, {
                    onUploadProgress: (itemUpload) =>{
                        this.fileProgress = Math.round( (itemUpload.loaded/itemUpload.total)*100);
                        this.fileCurrent = this.image.name + "%";
                    }
                }).then(response => (
                    this.imgName = '',
                    this.showDownload = false,
                    this.images_this = response.data.images
                    // this.is_src = true,
                    // this.url='/storage/' + response.data.path
                   
                )).catch(error => {
                    console.log(error);
                })
                this.fileProgress=0;
                this.fileCurrent='';
            },
            imgDelete(url){                
                axios.post('/navs/'+this.nav.id+'/image/delete', {
                    url: url
                }).then(response => (
                    this.images_this = response.data.images
                    // this.is_src = false,
                    // this.url=''
                    )).catch(error => {
                    console.log(error);
                })
            },

            settingSlider(){
                setTimeout(() => {
                    this.settings_show = true
                }, 500);
            },
            createTask(){
                $("#taskAddModal").modal('show');
                //this.show = true;
            },
            addTask(){
                axios.post('/tasks/add', {
                    nav: this.nav.id,
                    path: this.this_path,
                    name: this.nameAdd,
                    description: this.descriptionAdd 
                }).then(response => (
                    this.nameAdd = '',
                    this.this_path = '',
                    this.descriptionAdd = '',
                    this.show = false,
                    this.tasks_this = JSON.parse(response.data.tasks),
                    $("#taskAddModal").modal('hide')
                ));
            },
            cancel(){
                this.show = false;
                this.this_path = '';
                $("#taskAddModal").modal('hide');
            },
            reload(data){
                this.show_list = false;
                this.tasks_this = JSON.parse(data.tasks);
                this.tasks_done_this = JSON.parse(data.tasks_done);
               
                setTimeout(() => {
                    this.show_list = true;
                }, 0);                
            },
            editAngle(){
               axios.post('/setting/angle_edit',{
                angle: this.value_angle
                }).then(response => (
                    this.angle_this = this.value_angle
                ));
            },
            addImage(){                
                $("#imagesModal").modal('show');
            },
            editImg(data){
                console.log(data);
                this.editImgId = data.task_key;
            },
            editUrl(url){
                console.log
                if(this.editImgId>0)
                    this.tasks_this[this.editImgId].path=url;
                else
                    this.this_path=url;
                this.editImgId=0;
                $("#imagesModal").modal('hide');
            },
            editDistance(){
               axios.post('/setting/distance_edit',{
                distance: this.value_distance
                }).then(response => (
                    this.distance_this = this.value_distance
                ));
            }
        }
 

    }
</script>
