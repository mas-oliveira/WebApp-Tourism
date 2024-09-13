<template>
<v-card class="mx-auto" tile @click="isClickable ? openDialog() : null" :class="{ 'non-clickable': !isClickable }">
    <v-img :src="foto_capa" height="22vh"></v-img>
    <v-list-item color="rgba(0, 0, 0, .4)">
      <div class="v-list-item__content">
        <div class="title" style="font-size: 20px; font-weight: bold;">{{ nome }}</div>
        <div class="subtitle" style="font-size: 16px; color: #3f51b5;">{{ destino }}</div>
        <div class="caption" style="font-size: 14px; color: #757575;">{{ data_inicio }} - {{ data_termino }}</div>
        <div class="caption" style="font-size: 14px; color: #757575;">Vagas restantes: 
          <span v-if="vagas < 3" style="font-weight: bold; color: #f44336;">{{ vagas }}</span>
          <span v-else-if="vagas >= 3 && vagas <= 5" style="font-weight: bold; color: #ffeb3b;">{{ vagas }}</span>
          <span v-else-if="estado == 3" style="font-weight: bold; color: #4caf50; text-decoration: line-through;">{{ vagas }}</span>
          <span v-else style="font-weight: bold; color: #4caf50;">{{ vagas }}</span>
        </div>
      </div>
    </v-list-item>

    <dialog-pacote-component ref="dialogComponent" 
      :id="id"
      :nome="nome" 
      :descricao="descricao" 
      :destino="destino" 
      :data_inicio="data_inicio" 
      :data_termino="data_termino" 
      :acomodacao="acomodacao" 
      :preco="preco" 
      :vagas="vagas"
      :pacoteUserId="pacoteUserId"
      :myUserId="this.myUserId"
      :estado_atual="this.estado"
    />

  </v-card>
</template>





<script>


export default {
  /*components: {
    'dialog-pacote-component': DialogPacote
  },*/
  props: ['id', 'nome', 'foto_capa', 'descricao', 'destino', 'data_inicio', 'data_termino', 'acomodacao', 'preco', 'vagas', 'pacoteUserId','myUserId', 'estado'], //incluir o numero depois
  computed: {
    isClickable: function() {
      //console.log(this.estado)
      //console.log(this.estado == 0)
      return this.estado != 3;
    }
  },
  methods: {
    openDialog() {
      this.$refs.dialogComponent.dialog = true;
    }
  },
  created() {
   //this.$refs.dialogComponent.dialog = false;
   console.log(this.pacoteUserId);
  }
}
</script>

<style>
.non-clickable {
  opacity: 0.6;
  box-shadow: 0 0 10px rgba(0,0,0,0.5);
}
</style>