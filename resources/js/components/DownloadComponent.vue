<template>
    <div>
         <div class="container" v-if="!url">
            <div class="progress" style="height: 40px;">
                <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">{{ fileCurrent }}</div>
            </div> 
            <input type="file" name="image"  @change="sync">
            <button type="button" class="btn btn-default form-control" @click="download"><i class="fa fa-plus"></i>Загрузить</button>
        </div>

         <img v-bind:src="url" v-if="url">
         <input type='hidden' id="t_image" name="path" v-model="url" v-if="url">
         <div class="edit" @click="imgDelete" v-if="url">&#10006;</div>
    </div>
</template>

<script>
    export default {
        props:['path', 'task_id', 'nav'],
        data(){
            return{
                fileProgress: 0,
                fileCurrent: '',
                url:this.path
            }
        },
        mounted(){
            $("#imagesModal").modal('show');
        },
        methods:{
            sync(e) {
                e.preventDefault();
                this.image = e.target.files[0];

            },
            async download(){
                let data = new FormData()
                data.append('image', this.image)

                await axios.post('/tasks/'+this.nav+'/image/upload', data, {
                    onUploadProgress: (itemUpload) =>{
                        this.fileProgress = Math.round( (itemUpload.loaded/itemUpload.total)*100);
                        this.fileCurrent = this.image.name + "%";
                    }
                }).then(response => (
                   this.is_src = true,
                   this.url='/storage/' + response.data.path
                   
                )).catch(error => {
                    console.log(error);
                })
                this.fileProgress=0;
                this.fileCurrent='';
            },
            imgDelete(){
                if(this.task_id){
                    axios.post('/tasks/'+this.task_id+'/image/delete', {
                        url: this.url
                    }).then(response => (
                        this.is_src = false,
                        this.url='',
                        this.$emit('delete-img')
                    )).catch(error => {
                        console.log(error);
                    })
                }
                else{
                    axios.post('/tasks/image/delete', {
                        url: this.url
                    }).then(response => (
                        this.is_src = false,
                        this.url=''
                        )).catch(error => {
                        console.log(error);
                    })
                }
            }
        }

    }
</script>
