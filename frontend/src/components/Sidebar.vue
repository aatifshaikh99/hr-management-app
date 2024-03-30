<template>
   <aside class="sidebar">
      <div class="sidebar-header">HR Management</div>
      <ul class="nav flex-column">
        <li class="nav-item">
          <RouterLink v-if="isLoggedIn" :to="{ name: 'dashboard' }" class="nav-link">Dashboard</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink :to="{ name: 'profile' }" class="nav-link">My Profile</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink v-if="isLoggedIn && userRole === 'HR'" :to="{ name: 'employees' }" class="nav-link">Employees</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink v-if="isLoggedIn" :to="{ name: 'teams' }" class="nav-link">Teams</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink :to="{ name: 'projects' }" class="nav-link">Projects</RouterLink>
        </li>
        <!-- <li class="nav-item">
          <RouterLink :to="{ name: 'task' }" class="nav-link">My Tasks</RouterLink>
        </li> -->
        <!-- Add more navigation links as needed -->
      </ul>
    </aside>
</template>

<script setup>
import { ref, watch } from "vue";
import { RouterLink } from "vue-router";

const isLoggedIn = ref(false);
const userRole = ref('');

// Watch for changes in isLoggedIn and userRole
watch([isLoggedIn, userRole], ([isLoggedInValue, userRoleValue]) => {
  // Do something if necessary when the values change
});

// Function to initialize isLoggedIn and userRole based on local storage
const initializeAuthState = () => {
  const token = localStorage.getItem('token');
  const role = localStorage.getItem('role');
  
  if (token) {
    isLoggedIn.value = true;
    // Set user role if available
    if (role) {
      userRole.value = role;
    }
  }
};

// Call the initializeAuthState function to set initial values
initializeAuthState();

// You can call initializeAuthState() after successful login to update the values
</script>

<style scoped>
.sidebar {
  /* width: 250px; */
  background-color: #343a40;
  color: #fff;
  height: 100%;
}

.sidebar-header {
  padding: 25px;
  font-size: 1.5rem;
  font-weight: bold;
}

.nav-link {
  color: #fff;
  padding: 15px 25px;
}

.nav-link:hover {
  background-color: #495057;
}
</style>
