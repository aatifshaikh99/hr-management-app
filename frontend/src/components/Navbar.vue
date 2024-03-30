<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <RouterLink to="/" class="navbar-brand">HR Management</RouterLink>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <RouterLink to="/" class="nav-link">Home</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink to="/about" class="nav-link">About</RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink to="/careers" class="nav-link">Careers</RouterLink>
        </li>
        <!-- Add more navigation links as needed -->
      </ul>
      <!-- You can add additional items on the right side of the navbar, such as user profile, notifications, etc. -->
       <ul class="navbar-nav">
        <li v-if="!isLoggedIn" class="nav-item">
          <RouterLink to="/login" class="nav-link">Login</RouterLink>
        </li>
        <li v-if="!isLoggedIn" class="nav-item">
          <RouterLink to="/signup" class="nav-link">Register</RouterLink>
        </li>
        <li v-if="isLoggedIn" class="nav-item">
          <RouterLink to="/profile" class="nav-link">Profile</RouterLink>
        </li>
        <li v-if="isLoggedIn" class="nav-item">
          <a @click="logout" class="nav-link">Logout</a>
        </li>
      </ul>
    </div>
    <!-- </div> -->
  </nav>
</template>

<script setup>
import { RouterLink, useRouter } from 'vue-router';
import { ref, watch } from 'vue';
import axios from 'axios';

const router = useRouter();
const isLoggedIn = ref(false); // Check if token exists in local storage to determine login state
console.log(isLoggedIn.value);
// Function to handle logout
const logout = async () => {
  try {
    // Make an API request to revoke the user's token on the server side
    const response = await axios.post('http://127.0.0.1:8000/api/logout', null, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`, // Include the token in the request headers
        'Content-Type': 'application/json'
      }
    });

    if (response.status === 200) {
      // Clear token from local storage
      localStorage.removeItem('token');
      localStorage.removeItem('role');
      localStorage.removeItem('name');
      // Update isLoggedIn to false
      isLoggedIn.value = false;
      // Redirect to the login page or any other desired route
      // You may want to use Vue Router for this redirection
      router.push('/login');
      console.log('User logged out successfully');
    } else {
      throw new Error('Failed to logout');
    }
  } catch (error) {
    console.error('Logout error:', error);
  }
};

// Initialize isLoggedIn variable on component mount
// onMounted(() => {
//   isLoggedIn.value = !!localStorage.getItem('token');
// });

// Watch for changes in localStorage
// watchEffect(() => {
//   isLoggedIn.value = !!localStorage.getItem('token');
// });

watch([isLoggedIn], ([isLoggedInValue]) => {
  // Do something if necessary when the values change
});
// Function to initialize isLoggedIn and userRole based on local storage
const initializeAuthState = () => {
  const token = localStorage.getItem('token');
  // const role = localStorage.getItem('role');
  
  if (token) {
    isLoggedIn.value = true;
    // Set user role if available
    // if (role) {
    //   userRole.value = role;
    // }
  }
};

// Call the initializeAuthState function to set initial values
initializeAuthState();
</script>



<style scoped>
a {
  cursor: pointer;
}</style>
