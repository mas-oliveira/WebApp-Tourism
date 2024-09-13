<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <button type="button" class="btn btn-primary btn-sm-3 float-end" data-bs-toggle="modal" data-bs-target="#modalPublicacao">Adicionar</button>
                <br>
                <div class="mt-5" v-for="(publicacao, index) in publicacoes" :key="publicacao.id">
                    
                    <card-publicacao-component
                        :key="someKey"
                        :pub_user_id="publicacao.user_id"
                        :titulo="publicacao.titulo"
                        :imagens="publicacao.imagens"
                        :num_likes="publicacao.num_likes"
                        :user_likes="publicacao.user_likes"
                        :descricao="publicacao.descricao"
                        :nome="publicacao.nome"
                        :email="publicacao.email"
                        :comentarios="publicacao.comments"
                        :pub_id="publicacao.id"
                        :link_profile="link_profile[index]"
                        @updateComentarios="updateComentarios(index)"
                        @incrementLikes="publicacao.num_likes++"
                        @decrementLikes="publicacao.num_likes--"
                    >

                    </card-publicacao-component>

                </div>
                <modal-component modal-name="modalPublicacao" titulo="Adicionar publicacao">
                    <template v-slot:alertas>
                        <alert-component tipo="success" titulo="Publicacao adicionada :)" v-if="transacaoEstados == 'adicionado'"></alert-component>
                        <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar meter a publicacao" v-if="transacaoEstados == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group mb-2">
                            <input-container-component 
                                titulo="Título da publicação" 
                                id="novoTitulo"
                                id-help="novoTituloHelp"
                                texto-ajuda="Informe o nome do título. (max 40 caracteres)"
                            >
                                <input type="text" class="form-control" id="novoTitulo" aria-describedby="novoTituloHelp" placeholder="Titulo da publicacao" v-model="tituloPublicacao">
                            
                            </input-container-component>
                        </div>

                        <div class="form-group mb-2">
                            <input-container-component 
                                titulo="Descrição" 
                                id="novoDescricao"
                                id-help="novoDescricaoHelp"
                                texto-ajuda="Preencha uma descrição. (max 1000 caracteres)"
                                :conteudo="descricaoPublicacao"
                                max="1000"
                            >
                                <!--<input type="textarea" style="height: 15vh;" class="form-control" id="novoDescricao" aria-describedby="novoDescricaoHelp" placeholder="Descricao da publicacao" v-model="descricaoPublicacao">-->
                                <textarea style="height: 15vh;" class="form-control" id="novoDescricao" aria-describedby="novoDescricaoHelp" placeholder="Descricao da publicacao" v-model="descricaoPublicacao"></textarea>
                                
                            </input-container-component>
                        </div>

                        <div class="form-group">
                            <input-container-component 
                                titulo="Imagem" 
                                id="novoImagem"
                                id-help="novoImagemHelp"
                                texto-ajuda="Selecione imagens no formato PNG, JPEG ou JPG"
                            >
                            <br>

                                <input type="file" multiple class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">          

                            </input-container-component>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="salvar()" :disabled="!isFormValid()">Salvar</button>
                    </template>
                    
                </modal-component>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

    export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/publicacao',
                tituloPublicacao: '',
                descricaoPublicacao: '',
                arquivoImagem: [],
                transacaoEstados: '',
                transacaoDetalhes: [],
                userIdPost: 0,
                publicacoes: [],
                someKey: null,
                link_profile:[]
            }
        },
        methods: {
            carregarImagem(e) {
                this.arquivoImagem = e.target.files;
            },
            async updateComentarios(index) {
                this.someKey = Math.random();
                let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                }
                console.log("DEBUG -update: " + this.publicacoes[index].id);
                const response = await axios.get(`http://localhost:8000/api/v1/getComments?id=${this.publicacoes[index].id}`, config);
                let comments = JSON.parse(response.data);
                this.publicacoes[index].comments = comments;
                //this.publicacoes[index] = { ...this.publicacoes[index], comments: comments.reverse() };
            },
            async salvar() {
                let formData = new FormData();
                formData.append('titulo', this.tituloPublicacao);
                formData.append('descricao', this.descricaoPublicacao);
                console.log("tamanho: " +  this.arquivoImagem.length)
                for (let i = 0; i < this.arquivoImagem.length; i++) {
                    console.log(this.arquivoImagem[i])
                    formData.append('imagens[]', this.arquivoImagem[i]);
                }

                console.log(formData.get('imagens'));

                try {
                    let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                    let config = {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    }
                    const response = await axios.get('http://localhost:8000/api/v1/myUserId', config);
                    this.userIdPost = response.data;

                    formData.append('user_id', this.userIdPost);
                    
                    const postResponse = await axios.post(this.urlBase, formData, config);
                    this.transacaoEstados = 'adicionado';
                    this.transacaoDetalhes = postResponse.statusText;
                    console.log(postResponse);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    } catch (errors) {
                        this.transacaoEstados = 'erro';
                        console.log("ERRO --> " + errors.response.data)
                        this.transacaoDetalhes = JSON.stringify(errors.response.data.errors, null, 2);
                        console.log(errors);
                    }
            },

            async carregar_posts () {
                console.log(this.publicacoes);
                let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                let config = {
                    headers: {
                    'Authorization': 'Bearer ' + token
                    }
                }

                const getPublicacoes = await axios.get(this.urlBase, config);

                // Adicionado: Obter o nome do usuário para cada publicação
                for (let i = 0; i < getPublicacoes.data.length; i++) {
                    //const userId = getPublicacoes.data[i].user_id;
                    const getUser = await axios.get(`http://localhost:8000/api/v1/getUserInfo/${getPublicacoes.data[i].user_id}`, config);
                    getPublicacoes.data[i].nome = getUser.data.nome;
                    getPublicacoes.data[i].email = getUser.data.email;

                    getPublicacoes.data[i].imagens = JSON.parse(getPublicacoes.data[i].imagens).map(imagem => '/storage/' + imagem);
                    //console.log("oi" + getPublicacoes.data[i].comments)
                    getPublicacoes.data[i].comments = JSON.parse(getPublicacoes.data[i].comments);
                    this.link_profile[i] = `http://localhost:8000/profile/${getPublicacoes.data[i].user_id}`

                }

                this.publicacoes = getPublicacoes.data;
            },
            isFormValid() {
                return this.tituloPublicacao && this.descricaoPublicacao && this.arquivoImagem.length >= 1;
            }


        }, 
        created() {
            this.carregar_posts();
            this.interval = setInterval(() => {
                this.carregar_posts();
            }, 10000);
        },
        beforeDestroy() {
            clearInterval(this.interval);
        },
        watch: {
            publicacoes: {
                handler(newVal, oldVal) {
                    // Código para lidar com as alterações em 'this.publicacoes'
                    console.log('publicacoes mudou');

                    newVal.forEach((item, index) => {
                        if (item.comments) {
                            console.log("publicacoes[" + index + "].comments agora --> " + JSON.stringify(item.comments));
                        }
                    });
                   // console.log(JSON.stringify(oldVal[1].comments))
                    /*if (JSON.stringify(oldVal[1].comments) != null)
                        console.log('antes: ' + JSON.stringify(oldVal[1].comments));
                    if (JSON.stringify(newVal[1].comments) != null)
                        console.log('depois: ' + JSON.stringify(newVal[1].comments));*/
                },
                deep: true
            }
        }
    }
</script>
