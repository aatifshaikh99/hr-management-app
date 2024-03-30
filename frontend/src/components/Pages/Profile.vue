<template>
  <div class="container mt-4">
    <h2>Personal Info</h2>
    <div class="row mt-3" v-show="!isEditMode">
      <div class="col-md-6">
        <div class="card center">
          <p><strong>Name:</strong> {{ employee.name }}</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card center">
          <p><strong>Username:</strong> {{ employee.username }}</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card center">
          <p><strong>Email:</strong> {{ employee.email }}</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card center">
          <p><strong>Role:</strong> {{ employee.role }}</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card center">
          <p><strong>Team:</strong> Dream</p>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card center">
          <p><strong>Skills:</strong> {{ employee.skills }}</p>
        </div>
      </div>
      <div class="col-md-3 edit">
        <button class="btn block w-100 btn-primary" @click="enableEdit">
          Edit
        </button>
      </div>
    </div>
    <div class="card" v-show="isEditMode">
      <div class="card-body">
        <div class="form-group">
          <label for="name">Name:</label>
          <input
            type="text"
            class="form-control"
            id="name"
            v-model="employee.name"
          />
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input
            type="text"
            class="form-control"
            id="username"
            v-model="employee.username"
          />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input
            type="email"
            class="form-control"
            id="email"
            v-model="employee.email"
            disabled
          />
        </div>
        <div class="form-group">
          <label for="skills">Skills:</label>
          <input
            type="text"
            class="form-control"
            id="skills"
            v-model="employee.skills"
          />
        </div>
        <div class="form-group">
          <label for="role">Role:</label>
          <select
            class="form-control"
            id="role"
            v-model="employee.role"
            disabled
          >
            <option value="developer">Developer</option>
            <option value="HR">HR</option>
          </select>
        </div>
        <div class="form-group">
          <label for="team">Team:</label>
          <input
            type="text"
            class="form-control"
            id="team"
            v-model="employee.team"
            disabled
          />
        </div>
        <div>
          <button class="btn btn-primary" @click="saveChanges">Save</button>
          <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

const employee = ref({});
const isEditMode = ref(false);

const enableEdit = () => {
  isEditMode.value = true;
};

const cancelEdit = () => {
  console.log("Edit canceled");
  isEditMode.value = false;
};

// Fetch profile data on component mount
onMounted(async () => {
  await fetchProfile();
});

const fetchProfile = async () => {
  try {
    const token = localStorage.getItem("token");
    const headers = {
      Authorization: `Bearer ${token}`,
    };

    const response = await axios.get("http://127.0.0.1:8000/api/profile", {
      headers,
    });
    employee.value = response.data;
  } catch (error) {
    console.error("Error fetching profile:", error);
  }
};

const saveChanges = async () => {
  try {
    const token = localStorage.getItem("token");
    const headers = {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    };

    const response = await axios.put(
      "http://127.0.0.1:8000/api/profile",
      employee.value,
      { headers }
    );
    console.log(employee.value);
    console.log("Profile updated successfully");
    console.log(response.data);
    isEditMode.value = false;
  } catch (error) {
    console.error("Error updating profile:", error);
  }
};
</script>

<style scoped>
.card {
  background-color: #babdf1; 
  padding: 5px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
  /* align-items: center; */
  /* text-align: center; */
}
.center {
    text-align: center;
}
p {
  margin-top: 10px;
  margin-bottom: 10px;
}

.edit {
  display: flex;
  align-items: center;
}
</style>
