<template>
    <v-container>
      <v-card>
        <v-card-title class="headline">{{ pacote.nome }}</v-card-title>
        <v-card-text>{{ pacote.descricao }}</v-card-text>
        <v-card-subtitle>Preço: {{ pacote.preco }} €</v-card-subtitle>
  
        <v-card-title class="headline mt-4">Métodos de Pagamento</v-card-title>
        <v-radio-group v-model="paymentMethod">
          <v-radio value="creditCard">
            <template v-slot:label>
              <div style="padding-left: 10px;">Cartão de Crédito</div>
              <v-icon style="padding-left: 5px;" small>mdi-credit-card</v-icon>
            </template>
          </v-radio>
          <v-radio value="bankTransfer">
            <template v-slot:label>
              <div style="padding-left: 10px;">Transferência Bancária</div>
              <v-icon style="padding-left: 5px;" small>mdi-bank</v-icon>
            </template>
          </v-radio>
          <v-radio value="accountBallance">
            <template v-slot:label>
              <div style="padding-left: 10px;">Saldo</div>
              <v-icon style="padding-left: 5px;" small>mdi-cash</v-icon>
            </template>
          </v-radio>
        </v-radio-group>


  
        <v-form v-if="paymentMethod === 'creditCard'">
          <!-- Solicitar detalhes do cartão de crédito -->
        </v-form>
        <v-form v-else-if="paymentMethod === 'bankTransfer'">
          <!-- Fornecer detalhes para transferência bancária -->
        </v-form>
        <v-form v-else-if="paymentMethod === 'accountBallance'">
          <!-- Fornecer detalhes para transferência bancária -->
        </v-form>
  
        <v-card-title class="headline mt-4">Quantidade</v-card-title>
        <v-text-field
          v-model="quantidade"
          type="number"
          min="1"
          :max="pacote.vagas"
          label="Quantos pacotes quer comprar?"
        ></v-text-field>

        <v-card-subtitle v-if="pacote.preco && quantidade">Total final: {{ pacote.preco * quantidade }} €</v-card-subtitle>


        <v-card-actions>
          <v-btn v-if="pacote.user_id != this.user_id" color="primary" @click="finalizarCompra" :disabled="!paymentMethod || !quantidade">Finalizar Compra</v-btn>
        </v-card-actions>
        <v-snackbar
          v-model="snackbar"
          :color="snackbarColor"
          :timeout="5000"
          top
          right
        >
          {{ snackbarText }}
          <v-btn
            text
            @click="snackbar = false"
            right
          >
            <v-icon color="black">mdi-close</v-icon>
          </v-btn>
        </v-snackbar>
      </v-card>
    </v-container>
  </template>


<script>
import axios from 'axios';

  export default {
    props: ['id'],
    data() {
      return {
        paymentMethod: null,
        pacote: {},
        quantidade: 1,
        snackbar: false,
        snackbarColor: '',
        snackbarText: '',
        user_id: '' //user id on site
      }
    },
    methods: {
      async getUserId () {
        let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
          let config = {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    }
        const getUserId = await axios.get('http://localhost:8000/api/v1/myUserId', config);
        this.user_id = getUserId.data;
      },
      async finalizarCompra() {
        try {
          let paymentMethodMap = {
            'creditCard': 0,
            'bankTransfer': 1,
            'accountBallance': 2
          };

          let paymentMethodNumber = paymentMethodMap[this.paymentMethod];
          let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
          let formData = new FormData();
          formData.append('user_id', this.user_id);
          formData.append('pacote_id', this.pacote.id);
          formData.append('metodo_pagamento', paymentMethodNumber);
          formData.append('quantidade', this.quantidade);
          formData.append('preco_total', this.pacote.preco*this.quantidade);
          formData.append('pacote_user_id', this.pacote.user_id); //quem anunciou o pacote
          let response = await axios.post(`/api/v1/compra`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'Accept': 'application/json',
              'Authorization': 'Bearer ' + token
            }
          });
          this.snackbarColor = 'success';
          this.snackbarText = 'Compra efetuada com sucesso!';

          setTimeout(() => {
            window.location.href = 'http://localhost:8000/home';
          }, 2000);
        } catch (error) {
          this.snackbarColor = 'error';
          this.snackbarText = 'Erro ao alterar o estado.';
          console.error('Houve um problema com a operação de compra:', error);
        }
        this.snackbar = true;
    },
    fetchPacote() {
        fetch(`/api/v1/pacote/${this.id}`)
          .then(response => response.json())
          .then(data => this.pacote = data)
          .catch(error => console.error(error));
      }
  },
  created() {
    this.fetchPacote();
    this.getUserId();
  }
}
</script>
  