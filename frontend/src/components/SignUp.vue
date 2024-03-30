<!-- SignUp.vue -->
<template>
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-6">
        <img
          src="../assets/office.png"
          alt="Office Image"
          class="img-fluid w-100"
        />
      </div>
      <div class="col-md-6">
        <div class="container">
          <div class="card">
            <div class="card-body">
              <h2 class="card-title text-center mb-4">Sign Up</h2>
              <form
                @submit.prevent="handleSubmit"
                class="needs-validation"
                novalidate
              >
                <div class="mb-2">
                  <label for="name" class="form-label"
                    >Name<span class="text-danger">*</span>:</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="name"
                    required
                    placeholder="John Doe"
                  />
                </div>
                <div class="mb-2">
                  <label for="username" class="form-label"
                    >Username<span class="text-danger">*</span>:</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    v-model="username"
                    required
                    placeholder="johndoe21"
                  />
                </div>
                <div class="mb-2">
                  <label for="email" class="form-label"
                    >Email<span class="text-danger">*</span>:</label
                  >
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="email"
                    required
                    placeholder="johndoe45@gmail.com"
                  />
                </div>
                <div class="mb-2">
                  <label for="role" class="form-label">Role:</label>
                  <select
                    class="form-select rounded border-none p-1 w-100"
                    id="role"
                    v-model="role"
                  >
                    <option value="developer">Developer</option>
                    <option value="HR">HR</option>
                  </select>
                </div>
                <div class="mb-2">
                  <label for="skills" class="form-label"
                    >Skills<span class="text-danger">*</span>:</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="skills"
                    v-model="skills"
                    required
                    placeholder="HTML, CSS, Javascript, etc..."
                  />
                  <span class="form-text">
                    Enter your skills separated by commas (e.g., HTML, CSS,
                    JavaScript).
                  </span>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label"
                    >Password<span class="text-danger">*</span>:</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="password"
                    required
                    placeholder="Passwor@123"
                  />
                </div>
                <button
                  type="submit"
                  class="btn btn-primary w-100 d-block mx-auto"
                >
                  Sign Up
                </button>
              </form>
              <div class="mt-3 text-center">
                <span>Already have an account? </span>
                <RouterLink to="/login">Login</RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter(); // Create router instance

const name = ref("");
const username = ref("");
const email = ref("");
const password = ref("");
const role = ref("developer");
const skills = ref("");

const handleSubmit = async () => {
  try {
    const response = await axios.post("http://127.0.0.1:8000/api/signup", {
      name: name.value,
      username: username.value,
      email: email.value,
      role: role.value,
      skills: skills.value,
      password: password.value,
    });
    console.log(response.data); // Handle success response

    // Redirect to login route after successful signup
    router.push({ name: "login" }); // Replace 'Login' with the name of your login route

    // Reset input fields
    name.value = "";
    username.value = "";
    email.value = "";
    password.value = "";
    role.value = "developer";
    skills.value = "";
  } catch (error) {
    console.error(error.response); // Handle error response
  }
};
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
  border-top-left-radius: 150px;
  border-bottom-left-radius: 50px;
  border-top-right-radius: 50px;
  border-bottom-right-radius: 150px;
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
