<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg p-3 mb-5 bg-white rounded">
                    <div class="card-header text-center text-white bg-dark">Login</div>
                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus v-model="email">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Mantenha-me conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <v-alert v-if="loginError" type="error">
                                        {{ errorMessage }}
                                    </v-alert>
                                    <v-alert v-if="loginSucc" type="success">
                                        Login efetuado com sucesso
                                    </v-alert>
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="#">
                                        Esqueci a senha
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['csrf_token'], //data (semelhante)
        data() {
            return {
                email: '',
                password: '',
                loginError: false,
                errorMessage: '',
                loginSucc: false
            }
        },
        methods: {
            login(e) {

                let url = 'http://localhost:8000/api/login'
                let configuracao = {
                    method: 'post',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.password
                    })
                }

                fetch(url, configuracao)
                    .then(response => {
                        if(response.status === 403) {
                            this.loginError = true;
                            this.errorMessage = 'Erro no login: Usuário ou senha inválido!';
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if(data && data.token) {
                            document.cookie = 'token='+data.token+';SameSite=Lax'
                            this.loginError = false;
                            this.loginSucc=true;
                            e.target.submit()
                        }
                    })
            }
        }
    }
</script>
