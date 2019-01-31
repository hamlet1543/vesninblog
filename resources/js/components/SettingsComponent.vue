<template>
	<div>
		<input type="checkbox" id="settings-toggle" hidden>
		<nav class="settings-main">
 			<label for="settings-toggle" class="settings-main-toggle" onclick @click="foo"></label>
			<h2> 
				<a href="#">Настройки</a>
			</h2>

			<div class="setting_list">
				<div class="setting">
					<h4>Угол: {{ value_angle }}</h4>
 					<vue-slider 						
					    v-model="value_angle"
					    v-bind="options_angle"
					  ></vue-slider>					 
				</div>

				<div class="setting">
					<h4>Расстояние: {{ value_distance }}</h4>
 					<vue-slider
					    v-model="value_distance"
					    v-bind="options_distance"
					  ></vue-slider>
				</div>


			</div>

			<div class="mask-content"></div>
		</nav>

	</div>
</template>

<script>
import vueSlider from 'vue-slider-component'
export default {
	props:['angle', 'distance'], 
	data(){
		return{
			angle_this:'',
			distance_this:'',


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
		        show: false		       
		    },
		    value_distance: this.distance,
		      options_distance: {
		        eventType: 'auto',
		        width: 'auto',
		        height: 6,
		        dotSize: 34,		       
		        min: 0,
		        max: 100,
		        interval: 1,		       
		        speed: 0.5,
		        show: false	       
		      },

              

		}
	},
	components: {
    	vueSlider
  	},

	mounted(){	
		this.$emit('anglereload', {
            settings_angle: this.value_angle
        })	

   
	},

	methods:{
		foo(){
			setTimeout(() => {
				this.options_angle.show=!this.options_angle.show,
				this.options_distance.show=!this.options_distance.show
			}, 500);
		},

	},
	computed:{
		value_angle2: function (){
			console.log(this.value_angle);
		}
	}
}
</script>
