<template>
    <div>
        <div class="progress" style="height: 40px;" v-if="showDownload">
            <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">{{ fileCurrent }}</div>
        </div>
        <input type="file" name="image" v-show="false" @change="sync" ref="imgBtn" >
        {{ imgName }}
        <button type="button" class="btn btn-success" @click="download" v-if="!showDownload && imgName"><i class="fa fa-plus"></i>Загрузить</button>
        <button type="button" name="image" class="btn btn-warning" @click="cloneImgBtn">Выберите файл</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Отменить</button>
    </div>
</template>

<script>
    export default {
        props:['nav'],
        data(){
            return{
                showDownload:false,
                imgName:'',
                fileProgress: 0,
                fileCurrent: '',
            }
        },

        mounted(){
            
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
                data.append('nav', this.nav)

                await axios.post('/tasks/image/upload', data, 
                    {
                        onUploadProgress: (itemUpload) =>{
                        this.fileProgress = Math.round( (itemUpload.loaded/itemUpload.total)*100);
                        this.fileCurrent = this.image.name + "%";
                    }
                }).then(response => (
                    this.imgName = '',
                    this.showDownload = false,
                    this.$emit('addimg',{
                        images_load: response.data.images
                    })
                    

                   
                )).catch(error => {
                    console.log(error);
                })
                this.fileProgress=0;
                this.fileCurrent='';
            }
        }

    }
</script>
