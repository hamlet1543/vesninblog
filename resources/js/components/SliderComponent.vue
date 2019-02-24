<template>
	<div>
        <div class="setting">    
            <h4>{{ ru }}: {{ valueChange }}</h4>
            <font-awesome-icon icon="check" color="#dadada" class="icon" @click="edit"/>
            <font-awesome-icon icon="times" color="#dadada" class="icon" @click="valueChange = value"/>
        </div>        
        <vue-slider v-model="valueChange" v-bind="options"></vue-slider>        
    </div>                     
</template>

<script>
import vueSlider from 'vue-slider-component'
export default {
	props:['name', 'ru', 'valueLoad', 'min', 'max'], 
	data(){
		return{
			sliderShow:false,	
			value: this.valueLoad,
			valueChange: this.valueLoad,

		      options: {
		        eventType: 'auto',
		        width: 'auto',
		        height: 6,
		        dotSize: 34,		       
		        min: this.min,
		        max: this.max,
		        interval: 1,		       
		        speed: 0.5,
		        show: true		       
		    }              

		}
	},

	components: {
        vueSlider
    },
    watch: {
        valueChange: function (val) {
          	this.$emit('change'+this.name, {
        		value: val
        	});
        },            
    },
	mounted(){
	},

	methods:{
		edit(){
            axios.post('/setting/'+this.name+'_edit',{
            	value: this.valueChange
            }).then(response => (
                this.value = this.valueChange
            ));
        }
    }

}
</script>
