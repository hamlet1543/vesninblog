<template>
    <div>
        <div class="name" v-if="!folder_edit">
            <a v-bind:href="'/tasks/'+this.folder.id">{{this.folder.name}}</a>
            <div class="edit" @click="folder_edit = true">&#9998;</div>
            <div class="add" @click="folder_add = true">&#10133;</div>
            <div class="delete" @click="deleteFolder">&#10008;</div>
        </div>
        <div class="name folder_edit" v-if="folder_edit">
            <input type="text" name="folder_name_edit" id="f_name_edit" class="form-control" placeholder="Наименование" v-bind:value="this.folder.name">
            <div class="save" @click="editFolder">&#10004;</div>
            <div class="cancel" @click="folder_edit = false; nameEdit=''">&#10008;</div>
        </div>
        <div class="name folder_add" v-if="folder_add">
            <input type="text" name="folder_name_add" id="f_name_add" class="form-control" placeholder="Наименование" v-model="nameAdd">
            <div class="save" @click="addFolder">&#10004;</div>
            <div class="cancel" @click="folder_add = false; nameAdd=''">&#10008;</div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['folder','level'], 
        data(){
            return{
                folder_add:false,
                folder_edit:false,
                nameAdd:'',
            }
        },
        mounted(){
  
        },
        methods:{ 
            editFolder(){
                axios.post('/nav/'+this.folder.id+'/edit',{
                    name: $('#f_name_edit').val()
                }).then(response => (
                    this.folder_edit = false,
                    this.$emit('navreload', {
                        folders: response.data.navs
                    })
                ));
            },
            addFolder(){
                axios.post('/nav/add', {
                    name: this.nameAdd,
                    parent_id: this.folder.id,
                    level: this.level+1
                }).then(response => (
                    this.nameAdd = '',
                    this.folder_add = false,
                    this.$emit('navreload', {
                        folders: response.data.navs
                    })
                ));
            },
            deleteFolder(){
                axios.delete('/nav/'+this.folder.id).then(response => (
                    this.$emit('navreload', {
                        folders: response.data.navs
                    })
                ));
            }
        }
    }
</script>
