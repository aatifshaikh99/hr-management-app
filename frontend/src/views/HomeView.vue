<template>
<div class="row">
    <div class="col-md-3">
      <Sidebar />
    </div>

    <!-- Main Content Area -->
    <div class="col-md-9 mt-3">
      <!-- Header -->
      <header>
        <h1>Welcome, {{ employeeName }}</h1>
      </header>

      <div class="row">
        <RouterView />     
      </div>
    </div>
  </div>
</template>

<script setup>
import Sidebar from '@/components/Sidebar.vue';
const employeeName = localStorage.getItem('name'); 

</script>

<style scoped>

</style>