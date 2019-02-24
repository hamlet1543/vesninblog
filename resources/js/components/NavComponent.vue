<template>
	<div>
		<div v-if="alertShow" v-bind:class="'alert alert-'+status+' alert-dismissible fade show input-small'" role="alert">
	    	{{alertText}}
	    	<button type="button" class="close" @click="alertShow = false" aria-label="Close">
		        <span aria-hidden="true">
		        	<font-awesome-icon icon="times" color="#808080"/>
		        </span>
		    </button>
		</div>
	
    		
		
		<input type="checkbox" id="nav-toggle" hidden>
		<nav class="nav-main">
			<label for="nav-toggle" class="nav-main-toggle" onclick></label>
			<div class="user">
				<div v-if="!userEdit"> 
					
					<ul class="navbar-nav ml-auto">
					 	<li class="nav-item dropdown">

					 		<div class="dropdown open">
									<a id="navbarDropdown" class="nav-link dropdown-toggle login-name" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{login}} <span class="caret"></span>
                            	</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                    
                                    <button class="dropdown-item item-pointer" @click="userEdit = true, loginEdit = true">                                 
                                       Изменить login
                                    </button>
                                    <button class="dropdown-item item-pointer" @click="userEdit = true, passwordEdit = true">                                       
                                       Изменить пароль
                                    </button>
                                    <button class="dropdown-item item-pointer" @click="userEdit = true, avaEdit = true">                               
                                       Изменить аватар
                                    </button>

                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item item-pointer" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">                                                     
                                       Logout
                                    </button>

                                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    	<input type="hidden" name="_token" v-model="csrf">                             
                                    </form> 
                                </div>
                            </div>

                        </li>
					</ul>						
					
				</div>

				<div class="nav-edit-login" v-if="loginEdit">
					<input type="text" class="form-control" placeholder="login" v-model="loginChange">
					<div class="nav-edit-footer">
						<button type="button" class="btn btn-success" @click="loginOK">ОК</button>
						<button type="button" class="btn btn-danger" @click="loginCancel">Отмена</button>
					</div>
				</div>	
				<div class="nav-edit-password" v-if="passwordEdit">
					<input type="password" class="form-control" v-model="passwordCurrent" placeholder="Текущий пароль">
					<input type="password" class="form-control" v-model="passwordNew" placeholder="Новый пароль">
					<input type="password" class="form-control" v-model="passwordConfirm" placeholder="Повторить пароль">
					<div class="nav-edit-footer">
						<button type="button" class="btn btn-success" @click="passwordOK">ОК</button>
						<button type="button" class="btn btn-danger" @click="passwordCancel">Отмена</button>
					</div>
				</div>				
				<div class="nav-edit-ava" v-if="avaEdit">
					<div v-if="!isAva">
						<input type="file" name="image" v-show="false" @change="sync" ref="avaBtn" >
						<div class="nav-edit-footer">
							<button type="button" name="image" class="btn btn-warning" @click="cloneAvaBtn" v-if="!imgName">Выбрать</button>
							<button type="button" class="btn btn-success" @click="download" v-if="!showDownload && imgName"><i class="fa fa-plus"></i>Загрузить</button>
							<button type="button" class="btn btn-danger" @click="avaCancel">Отмена</button>
						</div>
						<div class="progress" style="height: 40px;" v-if="showDownload">
                            <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">{{ fileCurrent }}</div>
                        </div>
					</div>
					<div class="nav-edit-footer" v-if="isAva">					
						<button type="button" name="image" class="btn btn-warning" @click="avaDelete">Удалить</button>
						<button type="button" class="btn btn-danger" @click="avaCancel">Отмена</button>
					</div>							
				</div>						

				<div class="user_img">
					<img :src="avaSrc" alt="user" class="rounded-circle" style="object-fit:cover;height: 150px; width: 150px;">
				</div>
			</div>

			<div class="folder-list">
				<div class="folder" v-bind:style="{ paddingLeft: '0px', transition: 'opacity .5s .0s, transform .5s .0s' }">
					<div class="folder-edit" v-if="!folderEdit">
						<a href="/tasks/">{{nameFolderFirst}}</a>
						<font-awesome-icon icon="pencil-alt" color="#dadada" class="icon"  @click="folderEdit = true" v-if="!folderAdd"/>
						<font-awesome-icon icon="folder-plus" color="#dadada" class="icon" size="lg" @click="folderAdd = true" v-if="!folderAdd"/>
					</div>
					<div class="folder-edit" v-if="folderEdit">
						<input type="text" class="form-control-edit" placeholder="Наименование" v-model="nameFolderFirstChange">
						<font-awesome-icon icon="check" color="#dadada" class="icon"  @click="editFirstFolder"/>
						<font-awesome-icon icon="times" color="#dadada" class="icon" @click="folderEdit = false"/>
					</div>
					<div class="folder-edit" v-if="folderAdd">
						<input type="text" class="form-control-edit" placeholder="Наименование" v-model="folderNameAdd">
						<font-awesome-icon icon="check" color="#dadada" class="icon"  @click="addNewFolder"/>
						<font-awesome-icon icon="times" color="#dadada" class="icon" @click="folderAdd = false"/>
					</div>
				</div>
				<div class="folder" v-for="(folder,key) in this.folders"  v-bind:style="{ paddingLeft: folder.level*15+'px', transition: 'opacity .5s '+folder.level/10+'s, transform .5s '+folder.level/10+'s' }">
					<folder-tasks :folder="folder" :level="folder.level"  @navreload='reload'></folder-tasks>            
				</div>            
			</div>

			<div class="mask-content"></div>
		</nav>

	</div>
</template>

<script>
export default {
	props:['user', 'navs'], 
	data(){
		return{
			userEdit:false,
			loginEdit:false,
			passwordEdit:false,
			avaEdit:false,

			login: this.user.name,
			loginChange:this.user.name,
			passwordCurrent:'',	
			passwordNew:'',
			passwordConfirm:'',
			isAva:false,
			avaSrc:'/img/no-image.jpg',
			imgName:'',	
			showDownload:false,
			fileProgress: 0,
            fileCurrent: '',

			alertText:'',
			alertShow: false,
			status:'',

			folderAdd:false,
			folderEdit:false,
			nameFolderFirst: this.user.task,
			nameFolderFirstChange: this.user.task,			

			folders: this.navs,
			folderNameAdd:'',

			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')



		}
	},
	mounted(){
		if(this.user.avatar){
			this.avaSrc = this.user.avatar;
			this.isAva = true;
		} 
	},
	methods:{
		loginOK(){
			axios.post('/name_edit',{
				name: this.loginChange
			}).then(response => (
				this.alertText = response.data.text,
				this.status = response.data.status,
				this.userEdit = (!(this.status == 'success')),				
				this.alertShow=true,
				window.setTimeout(()=>{
		            this.alertShow=false;
		        },3000),
				
				this.login = this.loginChange,				
				this.loginEdit = false
			));     
		},
		loginCancel(){
			this.userEdit = false,
			this.loginEdit = false,
			this.loginChange = this.login			 
		},

		passwordOK(){

			if(this.passwordCurrent.trim().length>0){
				if(this.passwordNew==this.passwordConfirm){
				axios.post('/password_edit',{
					new: this.passwordNew,
					current: this.passwordCurrent
				}).then(response => (
					this.alertText = response.data.text,
					this.status = response.data.status,
					this.userEdit = (!(this.status == 'success')),				
					this.alertShow=true,
					window.setTimeout(()=>{
			           this.alertShow=false;
			        },3000),
					
					this.passwordCurrent = '',
					this.passwordNew = '',
					this.passwordConfirm = '',
					this.passwordEdit = false
				)); 
				}
				else{
					this.alertText='Введенные пароли не совпадают',
					this.status ='danger',
					this.alertShow=true,
					window.setTimeout(()=>{
			            this.alertShow=false;
			        },3000)
				}
			}
			else{
				this.alertText='Введите действующий пароль',
				this.status ='danger',
				this.alertShow=true,
				window.setTimeout(()=>{
		            this.alertShow=false;
		        },3000)
			}

		},

		passwordCancel(){
			this.userEdit = false,
			this.passwordEdit = false,
			this.passwordCurrent = '',		 
			this.passwordNew = '',		 
			this.passwordConfirm = ''		 
		},
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
                this.avaSrc = response.data.avatar,
                this.userEdit = false,
                this.avaEdit = false,
                this.isAva = true
                
               
            )).catch(error => {
                console.log(error);
            })
            this.fileProgress=0;
            this.fileCurrent='';
        },
        avaDelete(url){                
            axios.post('/avatar/delete').then(response => {
            	if (response.data.status=="delete"){
                	this.avaSrc = '/img/no-image.jpg',
                	this.isAva = false
            	}

                }).catch(error => {
                console.log(error);
            })
        },
        avaCancel(){                
			this.imgName = '',
            this.showDownload = false,
            this.userEdit = false,
            this.avaEdit = false
        },

		editFirstFolder(){
			axios.post('/nav/main/edit',{
				name: this.nameFolderFirstChange
			}).then(response => (
				$("#navName_0").html(this.nameFolderFirstChange),
				this.folderEdit = false,
				this.nameFolderFirst = this.nameFolderFirstChange
				));
		},
		addNewFolder(){
			axios.post('/nav/add', {
				name: this.folderNameAdd,
				parent_id: 0,
				level: 1
			}).then(response => (
				this.folderAdd = false,
				this.folders = JSON.parse(response.data.navs)
				));
		},
		reload(data){
			this.folders = JSON.parse(data.folders);
		}
	}
}
</script>
