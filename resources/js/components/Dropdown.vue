<template>
  <div class="dropdown relative">

      <!-- Trigger  -->
      <div class="dropdown-toggle"
            aria-haspopup="true"
           :aria-expanded="isOpen"
           @click.prevent="isOpen = !isOpen">
          <slot name="trigger"></slot>
      </div>

      <!-- Menu Links  -->
      <div v-show="isOpen"
          class="dropdown-menu absolute bg-card py-2 rounded shadow mt-2"
          :class=" align === 'left' ? 'pin-l' : 'pin-r'"
          :style="{width }"
      >
          <slot></slot>
      </div>

  </div>
</template>

<script>
   export default {
       props:{
           width:{default:'auto'},
           align:{ default:'left'}
       },
        data() {
            return { isOpen: false}
        },
       watch: {
           isOpen(isOpen){
               if(isOpen) {
                   document.addEventListener('click', this.closeIfClikedOutside);
               }
           }
       },
       methods: {
           closeIfClikedOutside(event){
               if(!event.target.closest('.dropdown')){
                   this.isOpen = false;
                   document.removeEventListener('click', this.closeIfClikedOutside);
               }
           }
       }

    }
</script>
