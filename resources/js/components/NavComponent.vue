<template>
	<div>
		<div id="edit_alert" v-if="alert_show" v-bind:class="'alert alert-'+status+' alert-dismissible fade show input-small'" role="alert">
	    	{{edit_text}}
	    	<button type="button" class="close" @click="alert_show = false" aria-label="Close">
		        <span aria-hidden="true">&times;</span>
		    </button>
		</div>
	
    		
		
		<input type="checkbox" id="nav-toggle" hidden>
		<nav class="nav-main">
			<label for="nav-toggle" class="nav-main-toggle" onclick></label>
			<div class="user">
				<div class="user_text" v-if="!user_edit"> 
					<div class="user_name">
						 <ul class="navbar-nav ml-auto">
						 	<li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{name}} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/logout"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                       Logout
                                    </a>

                                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                       
                                    </form> 
                                </div>
                            </li>
						 	</ul>
						
						<div class="edit" @click="user_edit = true">&#9998;</div>
					</div>
				</div>
				<div class="user_edit" v-if="user_edit">
					<div class="user_name">
						<input type="text" name="user_name" id="user_name" class="form-control" placeholder="login" v-model="this_name">
						<div class="save" @click="editName">&#10004;</div>
						<div class="cancel" @click="folder_edit = false">&#10008;</div>
					</div>
					<div class="user_password">
						<div class="user_name" v-if="!password_edit">
							Изменить пароль
							<div class="edit" @click="password_edit = true">&#9998;</div>
						</div>
						<div class="user_name" v-if="password_edit">
							Изменить пароль
							<div class="save" @click="editPassword">&#10004;</div>
							<div class="cancel" @click="password_edit = false">&#10008;</div>
							<input type="password" class="form-control" v-model="current_password" name="current_password" placeholder="Текущий пароль">
							<input type="password" class="form-control" v-model="new_password" name="new_password" placeholder="Новый пароль">
							<input type="password" class="form-control" v-model="password_confirm" name="password_confirmation" placeholder="Повторить новый пароль">
						</div>
					</div>
					<div class="user_name" v-if="!isAva">
						<input type="file" name="image" v-show="false" @change="sync" ref="avaBtn" >
						<button type="button" name="image" class="btn btn-warning" @click="cloneAvaBtn">Загрузить аватар</button>

						<button type="button" class="btn btn-success" @click="download" v-if="!showDownload && imgName"><i class="fa fa-plus"></i>Загрузить</button>
						<div class="progress" style="height: 40px;" v-if="showDownload">
                            <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">{{ fileCurrent }}</div>
                        </div>
					</div>
					<div class="user_name" v-if="isAva">						
						<button type="button" name="image" class="btn btn-danger" @click="avaDelete">Удалить аватар</button>
					</div>
					 
				</div>

				<div class="user_img">
					<img :src="ava_src" alt="user" class="rounded-circle" style="object-fit:cover;height: 150px; width: 150px;">
				</div>
			</div>

			<div class="folder_list">
				<div class="folder" folder_id="0" folder_parent_id="0" v-bind:style="{ paddingLeft: '0px', transition: 'opacity .5s .0s, transform .5s .0s' }">
					<div>
						<div class="name" v-if="!folder_edit">
							<a href="/tasks/">{{this.main_name}}</a>
							<div class="edit" @click="folder_edit = true">&#9998;</div>
							<div class="add" @click="folder_add = true">&#10133;</div>
						</div>
						<div class="name folder_edit" v-if="folder_edit">
							<input type="text" name="folder_name_edit" id="f_name_edit" class="form-control" placeholder="Наименование" v-model="nameEdit">
							<div class="save" @click="editFolder">&#10004;</div>
							<div class="cancel" @click="folder_edit = false">&#10008;</div>
						</div>
						<div class="name folder_add" v-if="folder_add">
							<input type="text" name="folder_name_add" id="f_name_add" class="form-control" placeholder="Наименование" v-model="nameAdd">
							<div class="save" @click="addFolder">&#10004;</div>
							<div class="cancel" @click="folder_add = false">&#10008;</div>
						</div>
					</div>
				</div>
				<div class="folder" v-for="(folder,key) in this.folders_this" v-bind:folder_id="folder.id" v-bind:folder_parent_id="folder.parent_id"  v-bind:style="{ paddingLeft: folder.level*15+'px', transition: 'opacity .5s '+folder.level/10+'s, transform .5s '+folder.level/10+'s' }">
					<folder-tasks :folder="folder" :level="folder.level"  @navreload='reload'></folder-tasks>            
				</div>            
			</div>

			<div class="mask-content"></div>
		</nav>

	</div>
</template>

<script>
export default {
	props:['folders', 'parent', 'test', 'user'], 
	data(){
		return{
			showDownload:false,
			imgName:'',	
			fileProgress: 0,
            fileCurrent: '',
			alert_show: false,
			current_password:'',	
			new_password:'',
			password_confirm:'',
			this_name:'',
			edit_text:'',
			status:'',
			user_edit:false,
			password_edit:false,
			folder_add:false,
			folder_edit:false,
			main_name:'',
			folders_this:'',
			nameAdd:'',
			nameEdit:'',
			name:'',
			isAva:false,
			ava_src:'/img/no-image.jpg'


		}
	},
	mounted(){
		axios.post('/nav').then(response => {  
			if (response.data.user.avatar){
				this.ava_src = response.data.user.avatar
				this.isAva = true
			}
			this.nameEdit = response.data.parent,              
			this.main_name = response.data.parent,
			this.name = response.data.user.name,             
			this.this_name = response.data.user.name,
			this.folders_this = JSON.parse(response.data.navs)

			});        
	},
	methods:{
		cloneAvaBtn($event){
                const elem = this.$refs.avaBtn;
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

                await axios.post('/avatar/upload', data, {
                    onUploadProgress: (itemUpload) =>{
                        this.fileProgress = Math.round( (itemUpload.loaded/itemUpload.total)*100);
                        this.fileCurrent = this.image.name + "%";
                    }
                }).then(response => (
                    this.imgName = '',
                    this.showDownload = false,
                    this.ava_src = response.data.avatar
                    // this.is_src = true,
                    // this.url='/storage/' + response.data.path
                   
                )).catch(error => {
                    console.log(error);
                })
                this.fileProgress=0;
                this.fileCurrent='';
            },
            avaDelete(url){                
                axios.post('/avatar/delete').then(response => {
                	if (response.data.status=="delete"){
                    	this.ava_src = '/img/no-image.jpg',
                    	this.isAva = false
                	}

                    // this.is_src = false,
                    // this.url=''
                    }).catch(error => {
                    console.log(error);
                })
            },
		editName(){
			axios.post('/name_edit',{
				name: this.this_name
			}).then(response => (
				this.edit_text = response.data.text,
				this.status = response.data.status,
				this.user_edit = (this.status == 'success'),				
				this.alert_show=true,
				window.setTimeout(()=>{
		            this.alert_show=false;
		        },3000),
				
				this.name = this.this_name,
				this.current_password = '',
				this.new_password = '',
				this.password_confirm = '',
				this.nameEdit = ''
			));     
		},
		editPassword(){

			if(this.current_password.trim().length>0){
				if(this.new_password==this.password_confirm){
				axios.post('/password_edit',{
					new: this.new_password,
					current: this.current_password
				}).then(response => (
					this.edit_text = response.data.text,
					this.status = response.data.status,
					this.user_edit = (this.status == 'success'),				
					this.alert_show=true,
					window.setTimeout(()=>{
			           this.alert_show=false;
			        },3000),
					
					this.current_password = '',
					this.new_password = '',
					this.password_confirm = '',
					this.nameEdit = ''

				)); 
				}
				else{
					this.edit_text='Введенные пароли не совпадают',
					this.status ='danger',
					this.alert_show=true,
					window.setTimeout(()=>{
			            this.alert_show=false;
			        },3000)
				}
			}
			else{
				this.edit_text='Введите действующий пароль',
				this.status ='danger',
				this.alert_show=true,
				window.setTimeout(()=>{
		            this.alert_show=false;
		        },3000)
			}

		},
		onLogin (data) {
			console.log('child component said login', data)
		},
		editFolder(){
			axios.post('/nav/main/edit',{
				name: this.nameEdit
			}).then(response => (
				this.folder_edit = false,
				this.main_name = this.nameEdit
				));
		},
		addFolder(){
			axios.post('/nav/add', {
				name: this.nameAdd,
				parent_id: 0,
				level: 1
			}).then(response => (
				this.folder_add = false,
				this.folders_this = JSON.parse(response.data.navs)
				));
		},
		reload(data){
			this.folders_this = JSON.parse(data.folders);
		}
	}
}
</script>
