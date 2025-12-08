<template>
  <header :class="['app-header', route.name.toLowerCase()]" >
    <nav>
      <router-link to="/">Itinéraire</router-link>
      <router-link to="/stats">Statistiques</router-link>
    </nav>
    <img :src="Logo" alt="logo_mob"/>
  </header>
</template>

<script setup>
import Logo from '@assets/images/logoFull.png'
import { useRoute } from 'vue-router';
const route = useRoute();
</script>

<style scoped>
.app-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding: 10px 100px;
  background-color: transparent;
  color: white;
  transition: background-color 0.3s ease;
  height: 80px;
  z-index: 1000;
}

.app-header:hover {
  background-color: #3B5A7E;
}

img{
  height: 50px;
}
nav {
  display: flex;
  align-items: center;
  gap: 20px;
  font-size: 20px;
}

nav a {
  color: white;
  text-decoration: none;
  transition: color 0.3s ease;
}

nav a:hover {
  color: black;
}

.app-header.stats  a {
  color: black;
}

.app-header.stats:hover  a{
  color: white;
}

.app-header.stats:hover  a:hover{
  color: black;
}


</style>
