<template>
    <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <button type="button" class="btn btn-primary btn-sm-3 float-end" data-bs-toggle="modal" data-bs-target="#modalPacote">Adicionar</button>
                    <br>
                    <select v-model="selectedState">
                        <option disabled value="">Selecione o estado</option>
                        <option>Por acontecer</option>
                        <option>A acontecer</option>
                        <option>Acabou</option>
                        <option>Cancelado</option>
                    </select>
                    <div class="mt-5" v-for="pacote in pacotes" :key="pacote.id">
                        <!--{{ pacote }}
                        <img style="width:4vh; height: 6vh;" :src="'/storage/'+pacote.foto_capa" />-->
                        <card-pacote-component 
                            :id="pacote.id"
                            :nome="pacote.nome" 
                            :foto_capa="'/storage/'+pacote.foto_capa"
                            :descricao="pacote.descricao"
                            :destino="pacote.destino"
                            :data_inicio="pacote.inicio"
                            :data_termino="pacote.termino"
                            :acomodacao="pacote.acomodacao"
                            :preco="pacote.preco"
                            :vagas="pacote.vagas"
                            :pacoteUserId="pacote.user_id"
                            :myUserId="this.myUserId"
                            :estado="pacote.estado"
                        >
                        </card-pacote-component>
                    </div>
                    <modal-component modal-name="modalPacote" titulo="Adicionar pacote">
                        <template v-slot:alertas>
                            <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Pacote adicionado :)" v-if="transacaoEstados == 'adicionado'"></alert-component>
                            <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar meter a publicacao" v-if="transacaoEstados == 'erro'"></alert-component>
                        </template>
                        <template v-slot:conteudo>
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Nome do pacote" 
                                    id="novoNome"
                                    id-help="novoNomeHelp"
                                    texto-ajuda="Informe o nome do pacote."
                                >
                                    <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome do pacote" v-model="nomePacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Descricao" 
                                    id="novoDescricao"
                                    id-help="novoDescricaoHelp"
                                    texto-ajuda="Escreva aqui a descricao."
                                >
                                    <input type="text" style="height: 15vh;" class="form-control" id="novoDescricao" aria-describedby="novoDescricaoHelp" placeholder="Descricao do pacote" v-model="descricaoPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Inicio data pacote" 
                                    id="novaDataInicio"
                                    id-help="novaDataInicioHelp"
                                    texto-ajuda="Data inicio"
                                >
                                    <input type="date" style="height: 6vh;" class="form-control" id="novaDataInicio" aria-describedby="novaDataInicioHelp" placeholder="Data inicio do pacote" v-model="dataInicioPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Termino data pacote" 
                                    id="novaDataTermino"
                                    id-help="novaDataTerminoHelp"
                                    texto-ajuda="Data termino"
                                >
                                    <input type="date" style="height: 6vh;" class="form-control" id="novaDataTermino" aria-describedby="novaDataTerminoHelp" placeholder="Data termino do pacote" v-model="dataTerminoPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Destino do pacote" 
                                    id="destinoPacote"
                                    id-help="novaDestinoHelp"
                                    texto-ajuda="Destino do pacote"
                                >
                                    <input type="text" style="height: 6vh;" class="form-control" id="destinoPacote" aria-describedby="novaDestinoHelp" placeholder="Insira a localização dos carteis a visitar (ex: Bogota, Colombia)" v-model="destinoPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Acomodação" 
                                    id="acomodacao"
                                    id-help="acomodacaoHelp"
                                    texto-ajuda="Selecione se tem ou não acomodação"
                                >
                                <br>
    
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="acomodacaoPacote" id="acomodacaoSim" value="true" v-model="acomodacaoPacote">
                                    <label class="form-check-label" for="acomodacaoSim">
                                        Sim
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="acomodacaoPacote" id="acomodacaoNao" value="false" v-model="acomodacaoPacote">
                                    <label class="form-check-label" for="acomodacaoNao">
                                        Não
                                    </label>
                                </div>
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Preço do pacote" 
                                    id="preco"
                                    id-help="precoHelp"
                                    texto-ajuda="Selecione o preço do seu pacote"
                                >
                                <br>
    
                                <input type="number" class="form-control" id="preco" aria-describedby="precoHelp" v-model="precoPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group mb-2">
                                <input-container-component 
                                    titulo="Número de vagas" 
                                    id="vagas"
                                    id-help="vagasHelp"
                                    texto-ajuda="Selecione o número de vagas do pacote"
                                >
                                <br>
    
                                <input type="number" class="form-control" id="vagas" aria-describedby="vagasHelp" v-model="vagasPacote">
                                
                                </input-container-component>
                            </div>
    
                            <div class="form-group">
                                <input-container-component 
                                    titulo="Imagem" 
                                    id="novoImagem"
                                    id-help="novoImagemHelp"
                                    texto-ajuda="Selecione uma imagem no formato PNG"
                                >
                                <br>
    
                                    <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                                
                                </input-container-component>
                            </div>
                        </template>
    
                        <template v-slot:rodape>
                            <button type="button" id="btnFechar" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-secondary" @click="limpar()">Limpar</button>
                            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
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
                urlBase: 'http://localhost:8000/api/v1/pacote',
                transacaoEstados: '',
                nomePacote: '',
                descricaoPacote: '',
                dataInicioPacote: '',
                dataTerminoPacote: '',
                acomodacaoPacote: '',
                precoPacote: '',
                vagasPacote: '',
                destinoPacote: '',
                pacotes: [],
                myUserId: '',
                selectedState: '',
                stateMapping: {
                        'Por acontecer': 0,
                        'A acontecer': 1,
                        'Acabou': 2,
                        'Cancelado': 3
                }
            }
        },
        watch: {
            selectedState: function(newVal, oldVal) {
                this.mudarDisplayPacotes();
            }
        },
        methods: {
                carregarImagem(e) {
                    this.arquivoImagem = e.target.files;
                },
                async mudarDisplayPacotes() {
                    
                    let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                    let config = {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token
                            }
                        }
                    let filtro = '';
                    let url = '';
                    if (this.selectedState) {
                        let filtro = this.stateMapping[this.selectedState];
                        url = `http://localhost:8000/api/v1/getPacotesFeed/${filtro}`;
                    } else {
                        url = `http://localhost:8000/api/v1/pacote`;
                    }
                    try {
                        let response = await axios.get(url, config);
                        console.log(response);
                        this.pacotes = response.data;
                    } catch (error) {
                        console.error(error);
                    }

                },
                async salvar() {
                    let formData = new FormData();
                    if (this.acomodacaoPacote == "true"){
                        this.acomodacaoPacote = 1;
                    } else {
                        this.acomodacaoPacote = 0;
                    }
    
                    formData.append('nome', this.nomePacote);
                    formData.append('descricao', this.descricaoPacote);
                    formData.append('inicio', this.dataInicioPacote);
                    formData.append('termino', this.dataTerminoPacote);
                    formData.append('acomodacao', this.acomodacaoPacote);
                    formData.append('preco', this.precoPacote);
                    formData.append('vagas', this.vagasPacote);
                    formData.append('destino', this.destinoPacote);
                    formData.append('foto_capa', this.arquivoImagem[0]);
                    formData.append('estado', "0")
                    
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
                        
    
                    try {
                        let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                        let config = {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token
                            }
                        }
    
                        const postResponse = await axios.post(this.urlBase, formData, config);
                        this.transacaoEstados = 'adicionado';
                        this.transacaoDetalhes = postResponse;
                        console.log(postResponse);
    
                           setTimeout(() => {
                            this.limpar();
                            this.fechar();
                        }, 1000);
                        
                        } catch (errors) {
                            this.transacaoEstados = 'erro';
                            this.transacaoDetalhes = errors.response;
                            console.log(errors);
                        }
                },
    
                async carregar_pacotes () {
                    //console.log("############")
                    //console.log(this.pacotes);
                    let token = document.cookie.replace(/(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                    let config = {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    }
                    const getUserId = await axios.get('http://localhost:8000/api/v1/myUserId', config);
                    //console.log(getUserId);
                    //let filtro = this.stateMapping[this.selectedState]
                    const getPacotes= await axios.get(`http://localhost:8000/api/v1/pacote`, config);
                    this.myUserId = getUserId.data;
                    //console.log(getPacotes.data)
                    this.pacotes = getPacotes.data;
                    console.log(this.pacotes[0]);
                   
                },
                limpar() {
                    this.nomePacote = '';
                    this.descricaoPacote = '';
                    this.dataInicioPacote = '';
                    this.dataTerminoPacote = '';
                    this.acomodacaoPacote = '';
                    this.precoPacote = '';
                    this.vagasPacote = '';
                    this.destinoPacote = '';
                    this.arquivoImagem = null;
                    this.estado = '';
                    this.transacaoEstados = '';
                },
                fechar(){
                    console.log("tentar carregar")
                    const botaoFechar = document.getElementById('btnFechar');
                    botaoFechar.click();
                }
    
        }, created() {
            this.carregar_pacotes();
            this.interval = setInterval(() => {
                this.mudarDisplayPacotes();
            }, 3000);
        }
    }
    </script>

<style>
select {
    width: 200px;
    height: 40px;
    border: none;
    margin: 10px;
    padding: 10px;
    background: #f8f8f8;
    border-radius: 5px;
    box-shadow: 0 5px 15px -5px #00000070;
    color: #525252;
    cursor: pointer;
}

select:hover {
    color: #8B008B;
}
</style>