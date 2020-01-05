@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<!--<users> </users> isto desaparece e passa a ser as linhas a baixo (ainda interligado com os conts router/routes-->
<ul class="nav">
    <li><router-link to="/">Home </router-link></li>
    <li><router-link to="/users" v-show="this.$store.state.user && this.$store.state.user.type == 'a'">Users </router-link> </li>
    <li><router-link to="/users/register" v-show="!this.$store.state.user">Register </router-link></li>
    <li><router-link to="/users/opAdminRegister" v-show="this.$store.state.user && this.$store.state.user.type == 'a'">Register Operator/Admin </router-link></li>
    <li><router-link to="/users/profile" v-show="this.$store.state.user">My profile </router-link> </li>
    <li><router-link to="/movements" v-show="this.$store.state.user && this.$store.state.user.type == 'u'">My virtual wallet </router-link></li> 
    <li><router-link to="/movementsStatistics" v-show="this.$store.state.user && this.$store.state.user.type == 'u'">Statistics </router-link></li> 
    <li><router-link to="/login" v-show="!this.$store.state.user">Login</router-link> </li>
    <li><router-link to="/logout" v-show="this.$store.state.user">Logout</router-link></li>
</ul>
<!-- <router-link to="/users" v-show="this.$store.state.user">Users -</router-link> -->
<!-- <router-link to="/movements" v-show="this.$store.state.user">Movements -</router-link> -->
<!-- <router-link to="/login" v-show="!this.$store.state.user">Login</router-link> -->
<!-- <router-link to="/logout" v-show="this.$store.state.user">Logout</router-link>-->
<!-- estas duas linhas de cima, criam-me as hiperligações que me aparecem no projeto -->

<router-view></router-view> <!-- como eu não queria que aparece os users em todas as paginas, uso este comando para passar a usar as rotas -->

@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop  
