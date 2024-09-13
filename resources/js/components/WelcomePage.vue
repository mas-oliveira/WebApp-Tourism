<template>
    12312312
    <v-container>
        <v-row>
            <v-col cols="12" sm="8" md="6">
                <v-card class="pa-2" outlined tile>
                    <v-img
                        class="white--text align-end"
                        height="200px"
                        src="https://via.placeholder.com/500"
                    >
                        <v-card-title>Bem-vindo ao Entrelinhas!</v-card-title>
                    </v-img>

                    <v-card-subtitle class="pb-0">
                        Conectando pessoas através do turismo.
                    </v-card-subtitle>

                    <v-card-text class="text--primary">
                        <div>Entrelinhas é uma rede social para amantes de viagens.</div>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="primary">Saiba mais</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        created() {
            console.log('Componente HomePage foi criado!');
        },
    }
</script>
  