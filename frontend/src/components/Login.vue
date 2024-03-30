<template>
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-6">
        <div class="container">
          <div class="card">
            <h2 class="card-title mt-4 text-center mb-4">Login</h2>
            <div class="card-body">
              <form
                @submit.prevent="handleSubmit"
                class="needs-validation"
                novalidate
              >
                <div class="mb-3">
                  <label for="username" class="form-label"
                    >Email/Username:</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    v-model="username"
                    required
                    placeholder="Enter Your Email/Username"
                  />
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Password:</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="password"
                    required
                    placeholder="Enter Your Password"
                  />
                </div>
                <button type="submit" class="btn btn-primary d-block w-100 mx-auto">
                  Login
                </button>
              </form>
              <div class="mt-3 text-center">
                <span>Don't have an account yet? </span>
                <RouterLink to="/signup">Register</RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <img
          src="../assets/office.png"
          alt="Office Image"
          class="img-fluid w-100"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { RouterLink, useRouter } from "vue-router";

const username = ref("");
const password = ref("");
const router = useRouter();

const handleSubmit = async () => {
  try {
    const response = await axios.post("http://127.0.0.1:8000/api/login", {
      identity: username.value,
      password: password.value,
    });

    // Store the token in local storage
    localStorage.setItem("token", response.data.token);
    localStorage.setItem("role", response.data.role);
    localStorage.setItem("name", response.data.name);
    console.log(response);
    // Redirect the user to the desired route
    router.push("/"); // Replace 'Dashboard' with the name of your dashboard route
  } catch (error) {
    console.error("Login failed:", error);
  }
};

// Check for token in local storage during application startup
// const token = localStorage.getItem("token");
// if (token) {
//   // Set token in axios header for subsequent API requests
//   axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
// }

// const handleLogout = () => {
//   // Clear token from local storage
//   localStorage.removeItem("token");

//   // Redirect user to the login page
//   router.push({ name: "Login" }); // Replace 'Login' with the name of your login route
// };
</script>

<style scoped>
.container-fluid {
  max-width: 1200px;
}

.card {
  background-color: #babdf1;
  border: none;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.card-title {
  color: #333; /* Change title color as needed */
}

.form-control {
  border: 1px solid #ced4da;
  border-radius: 5px;
}
.form-select {
  border: none;
}
.form-text {
  font-size: 13px;
  color: darkslategray;
}
.btn-primary {
  background-color: #007bff;
  border: none;
  border-radius: 5px;
}

.btn-primary:hover {
  background-color: #0056b3; /* Change button hover color as needed */
}
/* .container {
  max-width: 600px;
  background-color: #fff; 
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
} */

.img-fluid {
  border-top-left-radius: 50px;
  border-bottom-left-radius: 150px;
  border-top-right-radius: 150px;
  border-bottom-right-radius: 50px;
}

/* .form-control {
  border-radius: 5px;
} */

/* .btn-primary {
  border-radius: 5px;
} */

@media (max-width: 768px) {
  .container {
    max-width: 100%;
    border-radius: 0;
  }
  .img-fluid {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
}
</style>
