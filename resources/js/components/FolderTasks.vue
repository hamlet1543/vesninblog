<template>
    <div>
        <div class="folder-edit" v-if="!edit">
            <a v-bind:href="'/tasks/'+this.folder.id">{{name}}</a>
            <font-awesome-icon icon="pencil-alt" color="#dadada" class="icon"  @click="edit = true" v-if="!add"/>
            <font-awesome-icon icon="folder-plus" color="#dadada" class="icon" size="lg" @click="add = true" v-if="!add"/>
            <font-awesome-icon icon="times" color="#dadada" class="icon" @click="deleteFolder" v-if="!add"/>
        </div>
        <div class="folder-edit" v-if="edit">
            <input type="text" class="form-control-edit" placeholder="Наименование" v-model="name">
            <font-awesome-icon icon="check" color="#dadada" class="icon"  @click="editFolder"/>
            <font-awesome-icon icon="times" color="#dadada" class="icon" @click="edit = false; name = this.folder.name"/>
        </div>
        <div class="folder-edit" v-if="add">
            <input type="text" class="form-control-edit" placeholder="Наименование" v-model="nameAdd">
            <font-awesome-icon icon="check" color="#dadada" class="icon"  @click="addFolder"/>
            <font-awesome-icon icon="times" color="#dadada" class="icon" @click="add = false; nameAdd=''"/>
        </div>
    </div>
</template>

<script>
    export default {
        props:['folder','level'], 
        data(){
            return{
                add:false,
                edit:false,
                name: this.folder.name,
                nameAdd:'',
            }
        },
        mounted(){
  
        },
        methods:{ 
            editFolder(){
                axios.post('/nav/'+this.folder.id+'/edit',{
                    name: this.name
                }).then(response => (
                    this.edit = false,
                    $("#navName_"+this.folder.id).html(this.name),
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
                    this.add = false,
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
